<?php

namespace App\Support;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TradeInPhotoStorage
{
    protected const MAX_FILES = 6;

    protected const MAX_DIMENSION = 2400;

    protected const MAX_SOURCE_DIMENSION = 12000;

    protected const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    /**
     * @param  array<int, UploadedFile|null>  $files
     * @return array<int, string>
     */
    public static function storeMany(array $files): array
    {
        $files = array_values(array_filter($files, static fn ($file): bool => $file instanceof UploadedFile));

        if (count($files) > static::MAX_FILES) {
            throw ValidationException::withMessages([
                'photos' => ['Можна додати не більше 6 фото.'],
            ]);
        }

        $storedPaths = [];

        try {
            foreach ($files as $index => $file) {
                $storedPaths[] = static::storeOne($file, $index);
            }
        } catch (\Throwable $exception) {
            if ($storedPaths !== []) {
                Storage::disk('public')->delete($storedPaths);
            }

            throw $exception;
        }

        return $storedPaths;
    }

    protected static function storeOne(UploadedFile $file, int $index): string
    {
        if (! $file->isValid()) {
            static::throwValidationError($index, 'Не вдалося прочитати один із файлів.');
        }

        $realPath = $file->getRealPath();

        if (! is_string($realPath) || ! is_file($realPath)) {
            static::throwValidationError($index, 'Файл завантажився некоректно.');
        }

        $mimeType = (string) (mime_content_type($realPath) ?: '');

        if (! in_array($mimeType, static::ALLOWED_MIME_TYPES, true)) {
            static::throwValidationError($index, 'Підтримуються лише JPG, PNG або WEBP.');
        }

        $contents = file_get_contents($realPath);

        if (! is_string($contents) || $contents === '') {
            static::throwValidationError($index, 'Не вдалося обробити зображення.');
        }

        $imageInfo = @getimagesizefromstring($contents);

        if ($imageInfo === false) {
            static::throwValidationError($index, 'Файл не схожий на коректне зображення.');
        }

        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);

        if (
            $width < 1
            || $height < 1
            || $width > static::MAX_SOURCE_DIMENSION
            || $height > static::MAX_SOURCE_DIMENSION
        ) {
            static::throwValidationError($index, 'Розмір зображення виходить за дозволені межі.');
        }

        $image = @imagecreatefromstring($contents);

        if (! $image instanceof GdImage) {
            static::throwValidationError($index, 'Не вдалося безпечно відкрити зображення.');
        }

        $image = static::downscale($image, $width, $height);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $binary = static::encodeImage($image, $index);
        $directory = 'trade-in/' . date('Y/m');
        $extension = function_exists('imagewebp') ? 'webp' : 'png';
        $path = $directory . '/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($path, $binary, ['visibility' => 'public']);

        return $path;
    }

    protected static function downscale(GdImage $image, int $width, int $height): GdImage
    {
        $maxDimension = max($width, $height);

        if ($maxDimension <= static::MAX_DIMENSION) {
            return $image;
        }

        $scale = static::MAX_DIMENSION / $maxDimension;
        $nextWidth = max(1, (int) round($width * $scale));
        $nextHeight = max(1, (int) round($height * $scale));
        $scaled = imagescale($image, $nextWidth, $nextHeight, IMG_BICUBIC_FIXED);

        if ($scaled instanceof GdImage) {
            imagedestroy($image);

            return $scaled;
        }

        return $image;
    }

    protected static function encodeImage(GdImage $image, int $index): string
    {
        ob_start();

        $result = function_exists('imagewebp')
            ? imagewebp($image, null, 84)
            : imagepng($image, null, 6);

        $binary = ob_get_clean();
        imagedestroy($image);

        if (! $result || ! is_string($binary) || $binary === '') {
            static::throwValidationError($index, 'Не вдалося зберегти очищене зображення.');
        }

        return $binary;
    }

    protected static function throwValidationError(int $index, string $message): never
    {
        throw ValidationException::withMessages([
            'photos.' . $index => [$message],
            'photos' => [$message],
        ]);
    }
}
