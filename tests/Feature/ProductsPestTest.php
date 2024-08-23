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
    // $this->actingAs($this->admin)
    //     ->post(route('products.store'), $product)
});