<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class UserLinkTest extends TestCase
{
    use RefreshDatabase;
    public function testGetCreatedLink(): void
    {
        $user = User::factory()->create();
        Link::factory()->count(10)->create([
            'user_id' => $user->id,
            'title' => 'TestCreatedLink',
        ]);
        $response = $this->actingAs($user)->get('/');
        $response->assertSeeText('TestCreatedLink');
    }
    public function testSearchCreatedLink(): void
    {
        $user = User::factory()->create();
        Link::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);
        Link::factory()->create([
            'user_id' => $user->id,
            'title' => 'TestCreatedLink',
        ]);
        $response = $this->actingAs($user)->get('/?q=TestCreatedLink');
        $response->assertSeeText('TestCreatedLink');
    }
    public function testCreateNewLinkWithInvalidURL()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/links', [
            'destination_url' => 'https://invalid_url_pattern',
        ]);
        $response->assertSessionHasErrors('destination_url');
    }
    public function testCreateNewLink(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/links', [
            'title' => 'Google',
            'destination_url' => 'https://google.com',
        ]);
        $this->assertDatabaseHas('links', [
            'title' => 'Google',
            'destination_url' => 'https://google.com',
        ]);
        $response->assertRedirect('/');
    }
    public function testCreateNewLinkWithoutTitle(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/links', [
            'destination_url' => 'https://google.com',
        ]);
        $this->assertDatabaseHas('links', [
            'destination_url' => 'https://google.com',
        ]);
        $response->assertRedirect('/');
    }
    public function testDeleteCreatedLink(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user)->delete("/links/{$link->id}");
        $this->assertDatabaseMissing('links', [
            'title' => 'Google',
            'destination_url' => 'https://google.com',
        ]);
        $response->assertRedirect('/');
    }
}
