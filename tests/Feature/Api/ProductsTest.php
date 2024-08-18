<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_api_returns_products_list(): void
    {
        $product = Product::factory()->create();
        $response = $this->getJson(route('api-products.index'));
        
        $response->assertJson([$product->toArray()]);
    }
    
    public function test_api_product_store_successful(): void
    {
        $product = [
            'name' => 'Product 1',
            'price' => 1234
        ];
        $response = $this->postJson(route('api-products.store'), $product);
        
        $response->assertStatus(201);
        $response->assertJson($product);
    }
    
    public function test_api_invalid_store_returns_error(): void
    {
        $product = [
            'name' => '',
            'price' => 1234
        ];
        $response = $this->postJson(route('api-products.store'), $product);
        
        $response->assertStatus(422);
    }
}
