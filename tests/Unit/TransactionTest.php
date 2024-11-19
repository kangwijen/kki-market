<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\TransactionHeader;
use App\Models\TransactionDetail;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    public function test_can_create_transaction_header()
    {
        $user = User::factory()->create();
        $header = TransactionHeader::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('transaction_headers', [
            'id' => $header->id,
            'user_id' => $user->id
        ]);
    }

    public function test_transaction_header_has_many_details()
    {
        $header = TransactionHeader::factory()->create();
        $details = TransactionDetail::factory(3)->create([
            'transaction_header_id' => $header->id
        ]);

        $this->assertEquals(3, $header->transactionDetails->count());
    }

    public function test_can_create_transaction_detail()
    {
        $header = TransactionHeader::factory()->create();
        $product = Product::factory()->create();
        
        $detail = TransactionDetail::factory()->create([
            'transaction_header_id' => $header->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 10000,
            'total_price' => 20000
        ]);

        $this->assertDatabaseHas('transaction_details', [
            'transaction_header_id' => $header->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 10000,
            'total_price' => 20000
        ]);
    }

    public function test_transaction_detail_belongs_to_header()
    {
        $header = TransactionHeader::factory()->create();
        $detail = TransactionDetail::factory()->create([
            'transaction_header_id' => $header->id
        ]);

        $this->assertEquals($header->id, $detail->transactionHeader->id);
    }

    public function test_transaction_header_belongs_to_user()
    {
        $user = User::factory()->create();
        $header = TransactionHeader::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $header->user->id);
    }

    public function test_product_belongs_to_transaction_detail()
    {
        $product = Product::factory()->create();
        $detail = TransactionDetail::factory()->create([
            'product_id' => $product->id
        ]);

        $this->assertEquals($product->id, $detail->product->id);
    }
}
