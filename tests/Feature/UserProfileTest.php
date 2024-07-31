<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;
    public function testShowUserProfilePage(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertViewIs('profile');
    }
    public function testUpdateSomeUserProfileData(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'John Doe',
            'email' => $user->email,
            'username' => $user->username,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => $user->email,
            'username' => $user->username,
        ]);
        $response->assertRedirect('/profile');
    }
    public function testChangeUserPassword(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch('/profile', [
            'email' => $user->email,
            'username' => $user->username,
            'name' => $user->name,
            'password' => 'newpassword',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'username' => $user->username,
        ]);
        $response->assertRedirect('/profile');
    }
}
