<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use App\Traits\TestHelpers\CreateUserTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function test_api_returns_products_list(): void
    {
        $product = Product::factory()->create();
        
        // $response = $this->getJson(route('api-products._index'));
    }
}
