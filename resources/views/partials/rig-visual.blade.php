@php
    $variant = $variant ?? 'card';
    $caption = $caption ?? null;
@endphp

<div
    class="rig-visual rig-visual--{{ $variant }} {{ $class ?? '' }}"
    style="--tone-1: {{ $product['palette'][0] }}; --tone-2: {{ $product['palette'][1] }}; --tone-3: {{ $product['palette'][2] }};"
>
    <div class="rig-visual__beam"></div>
    <div class="rig-visual__stage">
        <div class="rig-visual__case">
            <div class="rig-visual__glass">
                <div class="rig-visual__fan"></div>
                <div class="rig-visual__fan"></div>
                <div class="rig-visual__fan"></div>
            </div>
            <div class="rig-visual__side-panel"></div>
            <div class="rig-visual__edge"></div>
        </div>
    </div>

    <div class="rig-visual__labels">
        <span class="rig-visual__series">{{ $product['series'] }}</span>
        <strong>{{ $product['name'] }}</strong>
        @if ($caption)
            <small>{{ $caption }}</small>
        @endif
    </div>
</div>
