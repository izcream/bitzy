<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;
    public function testRedirectToDestinationURL()
    {
        $link = Link::factory()->create();
        $response = $this->get("/go/$link->shortened_url");
        $response->assertStatus(302);
    }
    public function testRedirectToNonExistsShortLink()
    {
        $response = $this->get('/go/non-exists-short-link');
        $response->assertStatus(404);
    }
}
