<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductDetail;

class ProductTest extends TestCase
{
    private User $user;
    private Product $product;
    private ProductDetail $productDetail;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
        $this->productDetail = ProductDetail::factory()->create(['product_id' => $this->product->id]);
    }

    public function test_product_creation()
    {
        $this->assertInstanceOf(Product::class, $this->product);
    }

    public function test_product_has_product_detail()
    {
        $this->assertInstanceOf(ProductDetail::class, $this->product->productDetail);
    }

    public function test_product_belongs_to_product_type()
    {
        $this->assertInstanceOf(ProductType::class, $this->product->productType);
    }

    public function test_product_has_many_transaction_details()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->product->transactionDetails);
    }

    public function test_product_detail_belongs_to_product()
    {
        $this->assertInstanceOf(Product::class, $this->productDetail->product);
    }

    public function test_product_type_has_many_products()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->product->productType->products);
    }
}
