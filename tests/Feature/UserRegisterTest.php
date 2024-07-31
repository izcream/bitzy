<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testShowRegisterPage(): void
    {
        $response = $this->get('/register');
        $response->assertViewIs('auth.register');
    }
    public function testRegisterWithoutInputData(): void
    {
        $response = $this->post('/register', []);
        $response->assertSessionHasErrors(['name', 'username', 'email', 'password']);
    }
    public function testRegisterWithExistsEmail(): void
    {
        $user = User::factory()->create([
            'email' => 'exists@email.com'
        ]);
        $response = $this->post('/register', [
            'name' => 'user',
            'username' => 'user',
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors(['email']);
    }
    public function testRegisterWithExistsUsername(): void
    {
        User::factory()->create([
            'username' => 'existsusername'
        ]);
        $response = $this->post('/register', [
            'name' => 'user',
            'username' => 'existsusername',
            'email' => 'user@email.com',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors(['username']);
    }
    public function testRegister(): void
    {
        $response = $this->post('/register', [
            'name' => 'user',
            'username' => 'user',
            'email' => 'user@email.com',
            'password' => 'password',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'user@email.com'
        ]);
        $response->assertRedirect('/');
    }
}
