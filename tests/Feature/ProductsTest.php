<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Traits\TestHelpers\CreateUserTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    use CreateUserTrait;
    
    private User $user;
    private User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
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
        $response = $this->actingAs($this->admin)->get(route('products.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }
    
    public function test_non_admin_cannot_see_products_create_button(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.index'));
        
        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }
    
    public function test_admin_can_access_product_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('products.create'));
        
        $response->assertStatus(200);
    }
    
    public function test_non_admin_cannot_access_product_create_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.create'));
        
        $response->assertStatus(403);
    }
    
    public function test_create_product_successful(): void
    {
        $product = [
            'name' => 'Product 123',
            'price' => 1234
        ];
        
        $response = $this->actingAs($this->admin)->post(route('products.index'), $product);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        
        $this->assertDatabaseHas('products', $product);
        
        $lastProduct = Product::latest()->first();
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }
    
    public function test_product_edit_contains_correct_values(): void
    {
        $product= Product::factory()->create();
        
        $response = $this->actingAs($this->admin)->get(route('products.edit', $product));
        
        $response->assertStatus(200);
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
        $response->assertViewHas('product', $product);
    }
    
    public function test_produduct_update_validation_errror_redirects_back_to_form(): void
    {
        $product = Product::factory()->create();
        
        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'name' => '',
            'price' => ''
        ]);
        
        $response->assertStatus(302);
        $response->assertInvalid(['name', 'price']);
    }
    
    public function test_product_delete_successful(): void
    {
        $product = Product::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete(route('products.destroy', $product));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        
        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);
    }
}
