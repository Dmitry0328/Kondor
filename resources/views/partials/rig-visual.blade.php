@php
    $variant = $variant ?? 'card';
    $caption = $caption ?? null;
@endphp

<div
    class="build-visual build-visual--{{ $variant }}"
    style="--tone-1: {{ $product['palette'][0] }}; --tone-2: {{ $product['palette'][1] }}; --tone-3: {{ $product['palette'][2] }};"
>
    <div class="build-visual__stage">
        <div class="build-visual__shadow"></div>
        <div class="build-visual__case">
            <div class="build-visual__glass">
                <span class="build-visual__fan"></span>
                <span class="build-visual__fan"></span>
                <span class="build-visual__fan"></span>
            </div>
            <div class="build-visual__panel"></div>
        </div>
    </div>

    <div class="build-visual__meta">
        <strong>{{ $product['name'] }}</strong>
        @if ($caption)
            <span>{{ $caption }}</span>
        @endif
    </div>
</div>
