<?php

namespace App\Services;

use App\Models\Link;
use Carbon\Carbon;

class ShortLinkService
{
    const CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public static function generate(string $url): string
    {
        $hash = hash('sha256', $url, true);
        $base62 = self::base62Encode(uniqid($hash));
        $shortCode = substr($base62, 0, 6);
        while (Link::where('shortened_url', $shortCode)->exists()) {
            $shortCode = self::handleCollision($shortCode);
        }
        return $shortCode;
    }
    public static function base62Encode(string $content): string
    {
        $base = 62;
        $result = '';

        $decimal = 0;
        $length = strlen($content);

        for ($i = 0; $i < $length; $i++) {
            $decimal = ord($content[$i]) + ($decimal << 8);
        }

        while ($decimal > 0) {
            $result = self::CHARACTERS[$decimal % $base] . $result;
            $decimal = floor($decimal / $base);
        }
        return $result;
    }
    private static function handleCollision(string $shortCode): string
    {
        $randomCharacter = self::CHARACTERS[rand(0, strlen(self::CHARACTERS) - 1)];
        return "$shortCode$randomCharacter";
    }
}
