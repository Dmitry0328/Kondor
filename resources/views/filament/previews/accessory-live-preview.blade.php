@php
    $preview = $preview ?? [];
    $images = array_values((array) ($preview['image_urls'] ?? []));
    $primaryImage = $images[0] ?? null;
    $specs = array_values((array) ($preview['specs'] ?? []));
    $packageItems = array_values((array) ($preview['package_items'] ?? []));
@endphp

<div style="display:grid;gap:18px;">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div style="display:grid;gap:6px;">
            <span style="display:inline-flex;width:max-content;align-items:center;padding:6px 10px;border-radius:999px;background:#f3e8ff;color:#6f10c9;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">Kondor Device</span>
            <strong style="font-size:14px;color:#475569;">Storefront preview</strong>
        </div>
        <span style="display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;background:{{ !empty($preview['is_active']) ? '#ecfdf3' : '#f3f4f6' }};color:{{ !empty($preview['is_active']) ? '#047857' : '#64748b' }};font-size:12px;font-weight:800;">{{ !empty($preview['is_active']) ? 'Активний' : 'Чернетка' }}</span>
    </div>

    <div style="display:grid;gap:16px;padding:18px;border:1px solid #e2e8f0;border-radius:28px;background:linear-gradient(180deg,#fff,#f8fbff);box-shadow:0 20px 40px rgba(15,23,42,.08);">
        <div style="display:grid;gap:14px;">
            @if ($primaryImage)
                <div style="display:grid;place-items:center;min-height:240px;padding:18px;border:1px solid #dbe4ef;border-radius:24px;background:#fff;">
                    <img src="{{ $primaryImage }}" alt="{{ $preview['name'] ?? 'Accessory preview' }}" style="display:block;max-width:100%;max-height:220px;object-fit:contain;">
                </div>
            @endif

            @if (count($images) > 1)
                <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;">
                    @foreach (array_slice($images, 0, 4) as $image)
                        <div style="display:grid;place-items:center;aspect-ratio:1;border:1px solid #dbe4ef;border-radius:16px;background:#fff;padding:8px;">
                            <img src="{{ $image }}" alt="" style="display:block;max-width:100%;max-height:100%;object-fit:contain;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display:grid;gap:10px;">
            <div style="display:grid;gap:4px;">
                <span style="display:inline-flex;width:max-content;align-items:center;padding:5px 9px;border-radius:999px;background:#eef2ff;color:#6d28d9;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">{{ $preview['type_label'] ?? 'Девайс' }}</span>
                <h3 style="margin:0;font-size:28px;line-height:1.02;color:#111827;">{{ $preview['name'] ?? 'Назва девайсу' }}</h3>
                <div style="display:flex;flex-wrap:wrap;gap:8px;color:#64748b;font-size:13px;font-weight:700;">
                    <span>{{ $preview['vendor'] ?? 'Kondor' }}</span>
                    @if (!empty($preview['sku']))
                        <span>SKU: {{ $preview['sku'] }}</span>
                    @endif
                </div>
            </div>

            <p style="margin:0;color:#475569;font-size:15px;line-height:1.6;font-weight:700;">{{ $preview['summary'] ?? '' }}</p>

            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 16px;border:1px solid #e2e8f0;border-radius:20px;background:#fff;">
                <span style="color:#64748b;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;">Ціна</span>
                <strong style="font-size:28px;line-height:1;color:#111827;">{{ $preview['price'] ?? '0 грн' }}</strong>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="button" style="display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 18px;border:0;border-radius:999px;background:linear-gradient(180deg,#8424f0,#6816cb);color:#fff;font-size:14px;font-weight:800;">Інформація</button>
                <button type="button" style="display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 18px;border:1px solid #d6deea;border-radius:999px;background:#fff;color:#334155;font-size:14px;font-weight:800;">Комплектація</button>
            </div>
        </div>
    </div>

    <div style="display:grid;gap:14px;padding:18px;border:1px solid #e2e8f0;border-radius:24px;background:#fff;">
        <h4 style="margin:0;font-size:18px;color:#111827;">Попап “Інформація”</h4>
        <div style="display:grid;gap:8px;">
            @forelse ($specs as $row)
                <div style="display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);gap:12px;padding:10px 12px;border-radius:14px;background:{{ !empty($row['is_highlighted']) ? '#fef3c7' : '#f8fafc' }};">
                    <span style="color:#334155;font-size:13px;font-weight:800;">{{ $row['label'] }}</span>
                    <span style="color:#111827;font-size:13px;font-weight:800;">{{ $row['value'] }}</span>
                </div>
            @empty
                <div style="padding:12px 14px;border-radius:14px;background:#f8fafc;color:#64748b;font-size:13px;font-weight:700;">Тут одразу зʼявляться характеристики з форми.</div>
            @endforelse
        </div>
    </div>

    <div style="display:grid;gap:14px;padding:18px;border:1px solid #e2e8f0;border-radius:24px;background:#fff;">
        <h4 style="margin:0;font-size:18px;color:#111827;">Попап “Комплектація”</h4>
        <div style="display:grid;gap:8px;">
            @forelse ($packageItems as $row)
                <div style="display:flex;align-items:flex-start;gap:12px;padding:10px 12px;border-radius:14px;background:{{ !empty($row['is_highlighted']) ? '#fef3c7' : '#f8fafc' }};">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:999px;background:#fff;color:#6f10c9;font-size:11px;font-weight:900;text-transform:uppercase;">{{ strtoupper(substr((string) ($row['icon'] ?? 'g'), 0, 2)) }}</span>
                    <span style="color:#111827;font-size:13px;font-weight:800;line-height:1.5;">{{ $row['label'] }}</span>
                </div>
            @empty
                <div style="padding:12px 14px;border-radius:14px;background:#f8fafc;color:#64748b;font-size:13px;font-weight:700;">Комплект зʼявиться тут одразу після додавання рядків.</div>
            @endforelse
        </div>
    </div>
</div>
