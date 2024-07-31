<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class UserResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    public function testForgotPassword()
    {
        $user = User::factory()->create();
        $response = $this->get('/forgot-password');
        $response->assertViewIs('auth.forgot-password');
        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);
        $response->assertSessionHasNoErrors();
    }
    public function testResetPassword()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $response = $this->get("/reset-password?token=$token");
        $response->assertViewIs('auth.reset-password');
        $response = $this->post('/reset-password', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token,
        ]);
        $response->assertSessionHasNoErrors();
    }
}
