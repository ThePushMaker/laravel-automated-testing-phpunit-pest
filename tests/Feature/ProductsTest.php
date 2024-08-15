<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    
    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = $this->createUser();
    }
    
    public function test_homepage_contains_empty_table(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.index'));

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
        
        $response = $this->actingAs($this->user)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertSee($product->name);
        $response->assertViewHas('products', fn($collection) => $collection->contains($product));
    }
    
    public function test_paginated_products_table_doesnt_contain_11th_record(): void
    {
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();
        
        $response = $this->actingAs($this->user)->get(route('products.index'));
        
        $response->assertStatus(200);
        
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
           return !$collection->contains($lastProduct); 
        });
    }
    
    public function test_admin_can_see_products_create_button(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get(route('products.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }
    
    public function test_non_admin_cannot_see_products_create_button(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.index'));
        
        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }
    
    private function test_admin_can_access_product_create_page(): void
    {
        $admin = User::create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get(route('products.create'));
        
        $response->assertStatus(200);
    }
    
    private function test_non_admin_cannot_access_product_create_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.create'));
        
        $response->assertStatus(403);
    }
    
    private function createUser(): User
    {
        return User::factory()->create();
    }
    
}
