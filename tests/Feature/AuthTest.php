<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_login_redirects_to_products(): void
    {        
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123'
        ]);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
    }
    
    public function test_unauthenticated_user_cannot_access_product(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertRedirect(route('login'));
    }
}
