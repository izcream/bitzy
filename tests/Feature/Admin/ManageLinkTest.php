<?php

namespace Tests\Feature\Admin;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageLinkTest extends TestCase
{
    use RefreshDatabase;
    public function testGetLinkPage()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $link = Link::factory()->create();
        $response = $this->actingAs($user)->get('/admin/links');
        $response->assertViewIs('admin.links.index');
        $response->assertSee($link->title);
    }
    public function testSearchLink()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        Link::factory()->count(10)->create();
        $link = Link::factory()->create();
        $response = $this->actingAs($user)->get("/admin/links?q=$link->title");
        $response->assertViewIs('admin.links.index');
        $response->assertSee($link->title);
    }
    public function testEditUserLink()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $link = Link::factory()->create();
        $response = $this->actingAs($user)->get("/admin/links/$link->id/edit");
        $response->assertViewIs('admin.links.edit');
        $response = $this->actingAs($user)->patch("/admin/links/$link->id", [
            'shortened_url' => 'test_edit_shortned_url',
            'destination_url' => 'https://test_edit.com',
        ]);
        $this->assertDatabaseHas('links', [
            'shortened_url' => 'test_edit_shortned_url',
            'destination_url' => 'https://test_edit.com',
        ]);
        $response->assertRedirect('/admin/links');
    }
    public function testDeleteUserLink()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $link = Link::factory()->create();
        $response = $this->actingAs($user)->delete("/admin/links/$link->id");
        $this->assertDatabaseMissing('links', [
            'id' => $link->id,
        ]);
        $response->assertRedirect('/admin/links');
    }
}
