<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageUploadService
{
  public function store(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');
        $targetDir = public_path('images/'.$directory);

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true)) {
            $extension = 'jpg';
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'image';
        $filename = $baseName.'-'.Str::random(8).'.'.$extension;

        $file->move($targetDir, $filename);

        $relativePath = $directory.'/'.$filename;

        if ($oldPath && $this->shouldDeleteOld($oldPath, $directory)) {
            $this->delete($oldPath);
        }

        return $relativePath;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($this->normalizePath($path));

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function shouldDeleteOld(string $oldPath, string $newDirectory): bool
    {
        $normalized = $this->normalizePath($oldPath);

        return str_starts_with($normalized, 'images/uploads/')
            || str_starts_with($normalized, 'uploads/')
            || str_starts_with($normalized, 'images/'.$newDirectory.'/')
            || str_starts_with($normalized, $newDirectory.'/');
    }

    private function normalizePath(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($path, 'images/')) {
            return $path;
        }

        return 'images/'.$path;
    }
}
