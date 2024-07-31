<?php

namespace Tests\Feature\Admin;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageUserTest extends TestCase
{
    use RefreshDatabase;

    public function testGetUserPage()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertViewIs('admin.users.index');
    }
    public function testSearchUser()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        User::factory()->count(10)->create();
        $targetUser = User::factory()->create();
        $response = $this->actingAs($user)->get("/admin/users?q=$targetUser->name");
        $response->assertViewIs('admin.users.index');
        $response->assertSee($targetUser->name);
    }
    public function testCreateUser()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $response = $this->actingAs($user)->get('/admin/users/create');
        $response->assertViewIs('admin.users.create');
        $response = $this->actingAs($user)->post('/admin/users', [
            'name' => 'test_user',
            'email' => 'testuser@email.com',
            'username' => 'test_user',
            'password' => 'password',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'test_user',
            'email' => 'testuser@email.com',
            'username' => 'test_user',
        ]);
    }
    public function testViewUserDetail()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $targetUser = User::factory()->create();
        $userLink = Link::factory()->create([
            'user_id' => $targetUser->id
        ]);
        $response = $this->actingAs($user)->get("/admin/users/$targetUser->id?q=$userLink->title");
        $response->assertViewIs('admin.users.show');
        $response->assertSee($userLink->title);
    }
    public function testEditUser()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $targetUser = User::factory()->create();
        $response = $this->actingAs($user)->get("/admin/users/$targetUser->id/edit");
        $response->assertViewIs('admin.users.edit');
        $response = $this->actingAs($user)->patch("/admin/users/$targetUser->id", [
            'name' => 'test_edit_user',
            'username' => 'test_edit_user',
            'email' => 'test_edit_email@email.com'
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'test_edit_user',
            'username' => 'test_edit_user',
            'email' => 'test_edit_email@email.com'
        ]);
        $response->assertRedirect('/admin/users');
    }
    public function testChangeUserPassword()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $targetUser = User::factory()->create();
        $response = $this->actingAs($user)->patch("/admin/users/$targetUser->id", [
            'name' => 'test_edit_user',
            'username' => 'test_edit_user',
            'email' => 'test_edit_email@email.com',
            'password' => 'new_password'
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'test_edit_user',
            'username' => 'test_edit_user',
            'email' => 'test_edit_email@email.com'
        ]);
        $response->assertRedirect('/admin/users');
    }
    public function testDeleteUser()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $targetUser = User::factory()->create();
        $response = $this->actingAs($user)->delete("/admin/users/$targetUser->id");
        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id
        ]);
        $response->assertRedirect('/admin/users');
    }
}
