<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        Role::create(['id' => 1, 'name' => 'admin']);
        Role::create(['id' => 2, 'name' => 'user']);

        $this->user = User::create([
            'username' => 'user',
            'email' => 'user@test.com',
            'password' => Hash::make('Password123'),
            'role_id' => 2 
        ]);

        $this->admin = User::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password123'),
            'role_id' => 1 
        ]);

        UserDetail::create([
            'user_id' => $this->user->id,
            'balance' => 0,
            'verified' => false
        ]);

        UserDetail::create([
            'user_id' => $this->admin->id,
            'balance' => 0,
            'verified' => false
        ]);
    }

    public function test_user_can_update_own_details()
    {
        $this->actingAs($this->user);

        $response = $this->putJson("/api/user-details", [
            'username' => 'newusername',
            'email' => 'newemail@test.com',
            'currentPassword' => 'Password123'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User details updated successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'username' => 'newusername',
            'email' => 'newemail@test.com'
        ]);
    }

    public function test_user_cannot_update_without_current_password()
    {
        $this->actingAs($this->user);

        $response = $this->putJson("/api/user-details", [
            'username' => 'newusername',
            'email' => 'newemail@test.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['currentPassword']);
    }

    public function test_admin_can_update_user_details()
    {
        $this->actingAs($this->admin);

        $response = $this->putJson("/api/user-update/{$this->user->id}", [
            'username' => 'adminchanged',
            'email' => 'adminchanged@test.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User details updated successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'username' => 'adminchanged',
            'email' => 'adminchanged@test.com'
        ]);
    }

    public function test_regular_user_cannot_update_other_users()
    {
        $this->actingAs($this->user);

        $otherUser = User::create([
            'username' => 'other',
            'email' => 'other@test.com',
            'password' => Hash::make('Password123'),
            'role_id' => 2
        ]);

        $response = $this->putJson("/api/user-update/{$otherUser->id}", [
            'username' => 'changed',
            'email' => 'changed@test.com'
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_view_balance()
    {
        $this->actingAs($this->user);
        
        $response = $this->getJson("/api/user-details/balance");

        $response->assertStatus(200)
                 ->assertJsonStructure(['balance']);
    }

    public function test_sql_injection_attempt_in_username()
    {
        $this->actingAs($this->user);
        
        $response = $this->putJson("/api/user-details", [
            'username' => "'; DROP TABLE users; --",
            'email' => 'valid@test.com',
            'currentPassword' => 'Password123'
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    }

    public function test_xss_attempt_in_username()
    {
        $this->actingAs($this->user);
        
        $response = $this->putJson("/api/user-details", [
            'username' => "<script>alert('xss')</script>",
            'email' => 'valid@test.com',
            'currentPassword' => 'Password123'
        ]);

        $response->assertStatus(422);
    }

    public function test_prevent_privilege_escalation()
    {
        $this->actingAs($this->user);
        
        $response = $this->putJson("/api/user-details", [
            'username' => 'newname',
            'role_id' => 1,
            'currentPassword' => 'Password123'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'role_id' => 2
        ]);
    }

    public function test_prevent_balance_manipulation()
    {
        $this->actingAs($this->user);
        
        $response = $this->putJson("/api/user-details", [
            'username' => 'newname',
            'userDetail.balance' => 999999,
            'currentPassword' => 'Password123'
        ]);

        $this->assertDatabaseHas('user_details', [
            'user_id' => $this->user->id,
            'balance' => 0
        ]);
    }

    public function test_rate_limiting()
    {
        $this->actingAs($this->user);
        
        for ($i = 0; $i < 10; $i++) {
            $this->putJson("/api/user-details", [
                'username' => "test$i",
                'currentPassword' => 'Password123'
            ]);
        }

        $response = $this->putJson("/api/user-details", [
            'username' => 'final',
            'currentPassword' => 'Password123'
        ]);

        $response->assertStatus(429);
    }

    public function test_password_complexity()
    {
        $this->actingAs($this->user);
        
        $response = $this->putJson("/api/user-details", [
            'username' => 'newname',
            'currentPassword' => 'Password123',
            'newPassword' => 'weak',
            'newPasswordConfirm' => 'weak'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['newPassword']);
    }

    public function test_concurrent_session_handling()
    {
            $response1 = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'Password123'
            ]);
            $response1->assertRedirect('/products');
            
            $sessionId1 = session()->getId();

            $response2 = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'Password123'
            ]);
            $response2->assertRedirect('/products');
            
            $sessionId2 = session()->getId();

            $this->assertNotEquals($sessionId1, $sessionId2);

            $this->withSession(['_token' => $sessionId1])
                 ->get('/products')
                 ->assertOk();

            $this->withSession(['_token' => $sessionId2])
                 ->get('/products')
                 ->assertOk();
    }

    public function test_username_multiple_sql_injection_attempts()
    {
        $this->actingAs($this->user);
        
        $sqlInjections = [
            "' OR '1'='1",
            "'; DROP TABLE users; --",
            "' UNION SELECT * FROM users --",
            "admin'--",
            "' OR 1=1; --"
        ];

        foreach ($sqlInjections as $injection) {
            $response = $this->putJson("/api/user-details", [
                'username' => $injection,
                'email' => 'valid@test.com',
                'currentPassword' => 'Password123'
            ]);

            $response->assertStatus(422);
        }
    }

    public function test_multiple_xss_attempts()
    {
        $this->actingAs($this->user);
        
        $xssPayloads = [
            "<script>alert('xss')</script>",
            "<img src='x' onerror='alert(1)'>",
            "javascript:alert(1)",
            "<svg/onload=alert(1)>",
            "'-alert(1)-'"
        ];

        foreach ($xssPayloads as $payload) {
            $response = $this->putJson("/api/user-details", [
                'username' => $payload,
                'email' => 'valid@test.com',
                'currentPassword' => 'Password123'
            ]);

            $response->assertStatus(422);
        }
    }

    public function test_password_various_complexity_rules()
    {
        $this->actingAs($this->user);
        
        $weakPasswords = [
            'password',
            'Password',
            'password123',
            'Pass123',
            '12345678'
        ];

        foreach ($weakPasswords as $password) {
            $response = $this->putJson("/api/user-details", [
                'username' => 'newname',
                'currentPassword' => 'Password123',
                'newPassword' => $password,
                'newPasswordConfirm' => $password
            ]);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['newPassword']);
        }
    }

    public function test_authentication_brute_force()
    {
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson('/login', [
                'email' => 'user@test.com',
                'password' => 'wrong_password_' . $i
            ]);
        }

        $response = $this->postJson('/login', [
            'email' => 'user@test.com',
            'password' => 'wrong_password_final'
        ]);

        $response->assertStatus(429);
    }

    public function test_prevent_mass_assignment()
    {
        $this->actingAs($this->user);
        
        $response = $this->putJson("/api/user-details", [
            'username' => 'newname',
            'role_id' => 1,
            'is_admin' => true,
            'balance' => 999999,
            'verified' => true,
            'currentPassword' => 'Password123'
        ]);

        $user = User::find($this->user->id);
        $this->assertEquals(2, $user->role_id);
        $this->assertEquals(0, $user->userDetail->balance);
        $this->assertEquals(false, $user->userDetail->verified);
    }
}
