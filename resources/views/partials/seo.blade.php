@php
    $siteName = 'KondorPC';
    $defaultTitle = $siteName . ' - ігрові ПК та збірки під замовлення';
    $defaultDescription = 'KondorPC - готові ігрові ПК, збірки під замовлення, консультація та доставка по Україні.';

    $seoTitle = trim((string) ($title ?? $defaultTitle));
    $seoDescription = preg_replace('/\s+/u', ' ', trim((string) ($description ?? $defaultDescription))) ?: $defaultDescription;
    $seoCanonical = trim((string) ($canonical ?? url()->current()));
    $seoRobots = trim((string) ($robots ?? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'));
    $seoType = trim((string) ($type ?? 'website')) ?: 'website';
    $seoImageAlt = trim((string) ($imageAlt ?? $seoTitle)) ?: $seoTitle;
    $seoImage = $image ?? asset('images/kondor-mark-black.svg');
    $seoJsonLd = $jsonLd ?? [];

    if (! is_array($seoJsonLd)) {
        $seoJsonLd = [$seoJsonLd];
    }

    $seoJsonLd = array_values(array_filter($seoJsonLd, static fn ($item): bool => is_array($item) && $item !== []));

    $absoluteUrl = static function (?string $value): string {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $value) === 1) {
            return $value;
        }

        if (str_starts_with($value, '//')) {
            return request()->getScheme() . ':' . $value;
        }

        return url($value);
    };

    $seoCanonicalUrl = $absoluteUrl($seoCanonical);
    $seoImageUrl = $absoluteUrl((string) $seoImage);
@endphp
<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="googlebot" content="{{ $seoRobots }}">
<meta name="theme-color" content="#6f10c9">
<link rel="canonical" href="{{ $seoCanonicalUrl }}">

<meta property="og:locale" content="uk_UA">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonicalUrl }}">
<meta property="og:image" content="{{ $seoImageUrl }}">
<meta property="og:image:alt" content="{{ $seoImageAlt }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImageUrl }}">

@foreach ($seoJsonLd as $schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS) !!}</script>
@endforeach
