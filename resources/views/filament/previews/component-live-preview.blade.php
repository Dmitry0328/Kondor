@php
    $preview = $preview ?? [];
    $images = array_values((array) ($preview['image_urls'] ?? []));
    $primaryImage = $images[0] ?? null;
    $facts = array_values((array) ($preview['facts'] ?? []));
@endphp

<div style="display:grid;gap:18px;">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div style="display:grid;gap:6px;">
            <span style="display:inline-flex;width:max-content;align-items:center;padding:6px 10px;border-radius:999px;background:#e8f0ff;color:#3451b2;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">Configurator item</span>
            <strong style="font-size:14px;color:#475569;">Preview inside product options</strong>
        </div>
        <span style="display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;background:{{ !empty($preview['is_active']) ? '#ecfdf3' : '#f3f4f6' }};color:{{ !empty($preview['is_active']) ? '#047857' : '#64748b' }};font-size:12px;font-weight:800;">{{ !empty($preview['is_active']) ? 'Активний' : 'Чернетка' }}</span>
    </div>

    <div style="display:grid;gap:14px;padding:18px;border:1px solid #dbe4ef;border-radius:26px;background:linear-gradient(180deg,#fff,#f8fbff);box-shadow:0 18px 36px rgba(15,23,42,.08);">
        <div style="display:grid;grid-template-columns:96px minmax(0,1fr);gap:14px;align-items:start;padding:14px;border:1px solid #e2e8f0;border-radius:20px;background:#fff;">
            <div style="display:grid;place-items:center;aspect-ratio:1;border:1px solid #dbe4ef;border-radius:18px;background:#f8fafc;padding:8px;">
                @if ($primaryImage)
                    <img src="{{ $primaryImage }}" alt="{{ $preview['name'] ?? 'Component preview' }}" style="display:block;max-width:100%;max-height:100%;object-fit:contain;">
                @endif
            </div>
            <div style="display:grid;gap:6px;">
                <span style="display:inline-flex;width:max-content;align-items:center;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;">{{ $preview['type_label'] ?? 'Комплектуюча' }}</span>
                <strong style="font-size:18px;line-height:1.2;color:#111827;">{{ $preview['name'] ?? 'Назва комплектуючої' }}</strong>
                @if (!empty($preview['price']))
                    <strong style="font-size:22px;line-height:1;color:#111827;">{{ $preview['price'] }}</strong>
                @endif
                <div style="display:flex;flex-wrap:wrap;gap:8px;color:#64748b;font-size:12px;font-weight:700;">
                    <span>{{ $preview['vendor'] ?? 'Kondor' }}</span>
                    @if (!empty($preview['sku']))
                        <span>SKU: {{ $preview['sku'] }}</span>
                    @endif
                    @if (!empty($preview['slug']))
                        <span>{{ $preview['slug'] }}</span>
                    @endif
                </div>
                <p style="margin:2px 0 0;color:#475569;font-size:13px;line-height:1.55;font-weight:700;">{{ $preview['summary'] ?? '' }}</p>
            </div>
        </div>

        <div style="display:grid;gap:12px;padding:16px;border:1px solid #e2e8f0;border-radius:20px;background:#fff;">
            <h4 style="margin:0;font-size:17px;color:#111827;">Технічний блок</h4>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                @forelse ($facts as $row)
                    <div style="display:grid;gap:4px;min-width:120px;padding:10px 12px;border:1px solid #e2e8f0;border-radius:16px;background:#f8fafc;">
                        <span style="color:#64748b;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;">{{ $row['label'] }}</span>
                        <strong style="color:#111827;font-size:13px;line-height:1.35;">{{ $row['value'] }}</strong>
                    </div>
                @empty
                    <div style="padding:12px 14px;border-radius:14px;background:#f8fafc;color:#64748b;font-size:13px;font-weight:700;">Тут зʼявляться ключові параметри комплектуючої.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
