<?php

use App\Models\User;

beforeEach(function() {
    $this->user = User::factory()->create();
});

test('homepage contains empty table', function () {
    $this->actingAs($this->user)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});
