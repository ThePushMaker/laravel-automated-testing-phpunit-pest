<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_homepage_contains_empty_table(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        
        $response->assertSee(__('No products found'));
    }
    
    public function test_homepage_contains_non_empty_table(): void
    {
        // dd(DB::connection()->getConfig());// Muestra la configuracion de conexión
        
        $product = Product::create([
            'name' => 'Product 1',
            'price' => 123
        ]);
        
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertSee($product->name);
        $response->assertViewHas('products', fn($collection) => $collection->contains($product));
    }
    
    public function test_paginated_products_table_doesnt_contain_11th_record(): void
    {
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();
        
        $response = $this->get('/products');
        
        $response->assertStatus(200);
        
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
           return !$collection->contains($lastProduct); 
        });
    }
    
}
