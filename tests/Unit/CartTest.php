<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Tests\TestCase;

class CartTest extends TestCase
{
    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_can_create_cart()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
    }

    public function test_cart_belongs_to_user()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertEquals($this->user->id, $cart->user->id);
    }

    public function test_cart_belongs_to_product()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $this->assertInstanceOf(Product::class, $cart->product);
        $this->assertEquals($this->product->id, $cart->product->id);
    }

    public function test_can_update_cart_quantity()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $cart->update(['quantity' => 3]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 3
        ]);
    }

    public function test_can_delete_cart()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $cart->delete();

        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }
}
