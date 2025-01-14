<?php

use App\Models\Product;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
});

test('homepage contains empty table', function () {
    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});

test('homepage contains non empty table', function () {
    $product = createProduct(name: 'Product 1', price: 123);
    
    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertDontSee(__('No products found'))
        ->assertSee('Product 1')
        ->assertViewHas('products', function ($collection) use ($product){
            return $collection->contains($product);
        });
});

test('create product successful', function () {
    $product = [
        'name' => 'Product 123',
        'price' => 1234
    ];
    
    $this->actingAs($this->admin)
        ->post(route('products.store'), $product)
        ->assertRedirect(route('products.index'));
        
    $this->assertDatabaseHas('products', $product);
    
    $lastProduct = Product::latest()->first();
    expect($lastProduct->name)->toBe($product['name']);
    expect(floatval($lastProduct->price))->toBe(floatval($product['price']));
});