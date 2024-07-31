<?php

namespace Tests\Unit;

use App\Models\Link;
use App\Services\ShortLinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ShortLinkServiceTest extends TestCase
{
    use RefreshDatabase;
    public function testCreateShortLink()
    {
        $shortLink = ShortLinkService::generate('https://google.com');
        $this->assertIsString($shortLink);
    }
    public function testCreateWithSameLink1000Times()
    {
        $results = [];
        for ($i = 0; $i < 1000; $i++) {
            $results[] = ShortLinkService::generate('https://google.com');
        }
        $this->assertCount(1000, $results);
    }
}
