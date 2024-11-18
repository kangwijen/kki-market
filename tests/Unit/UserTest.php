<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\TransactionHeader;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    private $user;
    private $admin;

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
}
