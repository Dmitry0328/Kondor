@php
    $imageUrl = filled($imageUrl ?? null) ? (string) $imageUrl : null;
    $placeholderUrl = (string) ($placeholderUrl ?? '');
    $previewUrl = $imageUrl ?: $placeholderUrl;
@endphp

@include('filament.tables.columns.admin-image-preview', [
    'imageUrl' => $imageUrl,
    'imageUrls' => [$previewUrl],
    'placeholderUrl' => $placeholderUrl,
    'hasImage' => $imageUrl !== null,
    'caption' => (string) ($buildName ?? 'Build cover'),
    'alt' => (string) ($buildName ?? 'Build cover'),
    'clickToOpen' => true,
])
