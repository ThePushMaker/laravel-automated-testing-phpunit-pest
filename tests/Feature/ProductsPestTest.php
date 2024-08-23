<?php

use App\Models\User;

beforeEach(function() {
    $this->user = User::factory()->create();
});

test('homepage contains empty table', function () {
    $response = $this->actingAs($this->user)->get(route('products.index'));

    $response->assertStatus(200);
    
    $response->assertSee(__('No products found'));
});
