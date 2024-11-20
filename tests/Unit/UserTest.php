<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\TransactionHeader;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    public function test_user_has_fillable_attributes()
    {
        $fillable = ['username', 'email', 'password', 'role_id'];
        $user = new User;
        
        $this->assertEquals($fillable, $user->getFillable());
    }

    public function test_user_has_hidden_attributes()
    {
        $hidden = ['password', 'remember_token'];
        $user = new User;
        
        $this->assertEquals($hidden, $user->getHidden());
    }

    public function test_user_has_correct_casts()
    {
        $user = new User;
        $expected = [
            'id' => 'int',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
        
        $this->assertEquals($expected, $user->getCasts());
    }

    public function test_user_has_incrementing_id()
    {
        $user = new User;
        $this->assertTrue($user->getIncrementing());
        $this->assertEquals('id', $user->getKeyName());
    }

    public function test_user_belongs_to_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        
        $user->role()->associate($role);
        $user->save();
        
        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals($role->id, $user->role->id);
    }

    public function test_user_has_one_user_detail()
    {
        $user = User::factory()->create();
        $userDetail = UserDetail::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(UserDetail::class, $user->userDetail);
        $this->assertTrue($user->userDetail->is($userDetail));
        $this->assertEquals($user->id, $user->userDetail->user_id);
    }

    public function test_user_has_many_transactions_as_buyer()
    {
        $user = User::factory()->create();
        $transaction = TransactionHeader::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(TransactionHeader::class, $user->transactionsAsBuyer->first());
        $this->assertEquals($transaction->id, $user->transactionsAsBuyer->first()->id);
    }

    public function test_user_detail_has_fillable_attributes()
    {
        $fillable = ['user_id', 'balance', 'verified'];
        $userDetail = new UserDetail;
        
        $this->assertEquals($fillable, $userDetail->getFillable());
    }

    public function test_user_detail_belongs_to_user()
    {
        $user = User::factory()->create();
        $userDetail = UserDetail::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $userDetail->user);
        $this->assertTrue($userDetail->user->is($user));
        $this->assertEquals($user->id, $userDetail->user_id);
    }

    public function test_can_create_user_detail()
    {
        $user = User::factory()->create();
        $userDetail = UserDetail::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000.00,
            'verified' => true
        ]);

        $this->assertDatabaseHas('user_details', [
            'user_id' => $user->id,
            'balance' => 1000.00,
            'verified' => true
        ]);
    }

    public function test_default_values_are_set_correctly()
    {
        $user = User::factory()->create();
        $userDetail = UserDetail::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals(0.00, $userDetail->balance);
        $this->assertFalse($userDetail->verified);
    }
    
    public function test_can_create_role()
    {
        $role = Role::factory()->create([
            'name' => 'superadmin',
            'privilege_level' => 2
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'superadmin',
            'privilege_level' => 2
        ]);
    }

    public function test_role_has_required_attributes()
    {
        $role = Role::factory()->create();
        
        $this->assertNotNull($role->name);
        $this->assertNotNull($role->privilege_level);
        $this->assertIsString($role->name);
        $this->assertIsInt($role->privilege_level);
    }

    public function test_role_can_have_many_users()
    {
        $role = Role::factory()->create();
        $users = User::factory()->count(3)->create([
            'role_id' => $role->id
        ]);

        $this->assertEquals(3, $role->users()->count());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $role->users);
    }

    public function test_role_factory_works()
    {
        $role = Role::factory()->create();
        
        $this->assertNotNull($role->id);
        $this->assertNotNull($role->name);
        $this->assertNotNull($role->privilege_level);
    }
}
