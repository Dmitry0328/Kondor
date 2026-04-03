<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteImage extends Model
{
    protected $fillable = [
        'key',
        'disk',
        'path',
        'updated_by',
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute(): string
    {
        $url = Storage::disk($this->disk)->url($this->path);

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $parsed = parse_url($url);
            $path = $parsed['path'] ?? '';

            if ($path !== '') {
                $url = $path;

                if (! empty($parsed['query'])) {
                    $url .= '?' . $parsed['query'];
                }
            }
        }

        return $url;
    }
}
