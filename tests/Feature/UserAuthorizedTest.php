<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthorizedTest extends TestCase
{
    use RefreshDatabase;
    public function testUserTryAccessManageLinkPage()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        //index
        $response = $this->actingAs($user)->get('/admin/links');
        $response->assertStatus(403);
        //create
        $response = $this->actingAs($user)->get('/admin/links/create');
        $response->assertStatus(403);
        //store
        $response = $this->actingAs($user)->post('/admin/links');
        $response->assertStatus(403);
        //edit
        $response = $this->actingAs($user)->get("/admin/links/$link->id/edit");
        $response->assertStatus(403);
        //update
        $response = $this->actingAs($user)->patch("/admin/links/$link->id");
        $response->assertStatus(403);
        //destroy
        $response = $this->actingAs($user)->delete("/admin/links/$link->id");
        $response->assertStatus(403);
    }
    public function testUserTryAccessManageUserPage()
    {
        $user = User::factory()->create();
        //index
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertStatus(403);
        //create
        $response = $this->actingAs($user)->get('/admin/users/create');
        $response->assertStatus(403);
        //store
        $response = $this->actingAs($user)->post('/admin/users');
        $response->assertStatus(403);
        //edit
        $response = $this->actingAs($user)->get("/admin/users/$user->id/edit");
        $response->assertStatus(403);
        //update
        $response = $this->actingAs($user)->patch("/admin/users/$user->id");
        $response->assertStatus(403);
        //destroy
        $response = $this->actingAs($user)->delete("/admin/users/$user->id");
        $response->assertStatus(403);
    }
}
