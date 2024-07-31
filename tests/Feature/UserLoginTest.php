<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;
    public function testShowUserLoginPage(): void
    {
        $response = $this->get('/login');
        $response->assertViewIs('auth.login');
    }
    public function testRedirectUserIfNotLogin(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }
    public function testLoginWithoutInputData(): void
    {
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors(['username', 'password']);
    }
    public function testLoginWithNonExistsUser(): void
    {
        $response = $this->post('/login', [
            'username' => 'not_existing_user',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors(['username']);
    }
    public function testLoginWithInvalidPassword(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'invalid_password',
        ]);
        $response->assertSessionHasErrors(['password']);
    }
    public function testLogin(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => 'password',
        ]);
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }
    public function testRedirectIfLoggedIn(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/');
    }
    public function testLogout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/login');
    }
}
