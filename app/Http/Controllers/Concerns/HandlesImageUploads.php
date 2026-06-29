<?php

namespace App\Http\Controllers\Concerns;

use App\Services\ImageUploadService;
use Illuminate\Http\Request;

trait HandlesImageUploads
{
    protected function mergeUploadedImages(
        Request $request,
        array $data,
        array $fields,
        string $directory,
        ?object $model = null
    ): array {
        $uploader = app(ImageUploadService::class);

        foreach ($fields as $field) {
            $uploadKey = $field.'_upload';

            if ($request->hasFile($uploadKey)) {
                $request->validate([
                    $uploadKey => ['image', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
                ]);

                $data[$field] = $uploader->store(
                    $request->file($uploadKey),
                    'uploads/'.$directory,
                    $model?->{$field} ?? null
                );
            }
        }

        return $data;
    }
}
