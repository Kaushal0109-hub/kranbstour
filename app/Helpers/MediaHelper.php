<?php

namespace App\Helpers;

class MediaHelper
{
    /**
     * Resolve a public/images path to a full asset URL (call at runtime only, not from config files).
     */
    public static function url(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = str_starts_with($path, 'images/')
            ? $path
            : 'images/' . ltrim($path, '/');

        return asset($normalized);
    }

    /**
     * Recursively resolve image paths inside config arrays to asset URLs.
     */
    public static function resolve(mixed $value): mixed
    {
        if (is_string($value)) {
            if (preg_match('/\.(jpe?g|png|webp|gif|svg)$/i', $value)) {
                return self::url($value);
            }

            return $value;
        }

        if (is_array($value)) {
            return array_map([self::class, 'resolve'], $value);
        }

        return $value;
    }

    /**
     * Unsplash CDN — legacy fallback only.
     */
    public static function unsplash(string $photoId, int $width, int $height, int $quality = 85): string
    {
        return "https://images.unsplash.com/{$photoId}?auto=format&fit=crop&w={$width}&h={$height}&q={$quality}";
    }
}
