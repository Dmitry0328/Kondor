@php
    $preview = $preview ?? [];
    $images = array_values((array) ($preview['image_urls'] ?? []));
    $primaryImage = $images[0] ?? null;
    $about = (array) ($preview['about'] ?? []);
    $toneMap = [
        'violet' => ['from' => '#7c3aed', 'to' => '#4c1d95', 'soft' => '#f3e8ff'],
        'magenta' => ['from' => '#db2777', 'to' => '#831843', 'soft' => '#fce7f3'],
        'amber' => ['from' => '#f59e0b', 'to' => '#92400e', 'soft' => '#fef3c7'],
        'peach' => ['from' => '#fb7185', 'to' => '#be123c', 'soft' => '#ffe4e6'],
        'emerald' => ['from' => '#10b981', 'to' => '#065f46', 'soft' => '#d1fae5'],
    ];
    $tone = $toneMap[$preview['tone'] ?? 'violet'] ?? $toneMap['violet'];
@endphp

<div style="display:grid;gap:18px;">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div style="display:grid;gap:6px;">
            <span style="display:inline-flex;width:max-content;align-items:center;padding:6px 10px;border-radius:999px;background:{{ $tone['soft'] }};color:{{ $tone['from'] }};font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">Storefront Build</span>
            <strong style="font-size:14px;color:#475569;">Preview product page</strong>
        </div>
        <span style="display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;background:{{ !empty($preview['is_active']) ? '#ecfdf3' : '#f3f4f6' }};color:{{ !empty($preview['is_active']) ? '#047857' : '#64748b' }};font-size:12px;font-weight:800;">{{ !empty($preview['is_active']) ? 'Опубліковано' : 'Чернетка' }}</span>
    </div>

    <div style="display:grid;gap:16px;padding:18px;border:1px solid #dbe4ef;border-radius:28px;background:linear-gradient(180deg,#fff,#f8fbff);box-shadow:0 20px 40px rgba(15,23,42,.08);">
        <div style="display:grid;gap:14px;">
            <div style="display:grid;place-items:center;min-height:240px;padding:18px;border:1px solid #dbe4ef;border-radius:24px;background:linear-gradient(180deg,{{ $tone['from'] }},{{ $tone['to'] }});overflow:hidden;">
                @if ($primaryImage)
                    <img src="{{ $primaryImage }}" alt="{{ $preview['name'] ?? 'Build preview' }}" style="display:block;max-width:100%;max-height:220px;object-fit:contain;background:rgba(255,255,255,.92);border-radius:20px;padding:10px;">
                @endif
            </div>
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
            <span style="display:inline-flex;width:max-content;align-items:center;padding:6px 10px;border-radius:999px;background:{{ $tone['soft'] }};color:{{ $tone['from'] }};font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">Артикул {{ $preview['product_code'] ?? '000000' }}</span>
            <h3 style="margin:0;font-size:32px;line-height:1.02;color:#111827;">{{ $preview['name'] ?? 'Назва збірки' }}</h3>
            <strong style="font-size:34px;line-height:1;color:#111827;">{{ $preview['price'] ?? '0 ₴' }}</strong>
        </div>

        <div style="display:grid;gap:10px;padding:16px;border:1px solid #e2e8f0;border-radius:20px;background:#fff;">
            <h4 style="margin:0;font-size:17px;color:#111827;">Ключові комплектуючі</h4>
            <div style="display:grid;gap:8px;">
                @foreach ([
                    'GPU' => $preview['gpu'] ?? '',
                    'CPU' => $preview['cpu'] ?? '',
                    'RAM' => $preview['ram'] ?? '',
                    'Storage' => $preview['storage'] ?? '',
                ] as $label => $value)
                    <div style="display:grid;grid-template-columns:72px minmax(0,1fr);gap:12px;padding:10px 12px;border-radius:14px;background:#f8fafc;">
                        <span style="color:#64748b;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;">{{ $label }}</span>
                        <span style="color:#111827;font-size:13px;font-weight:800;line-height:1.45;">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display:grid;gap:10px;padding:16px;border:1px solid #e2e8f0;border-radius:20px;background:#fff;">
            <h4 style="margin:0;font-size:17px;color:#111827;">Характеристики сторінки товару</h4>
            <div style="display:grid;gap:8px;">
                @forelse (($preview['specs'] ?? []) as $row)
                    <div style="display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);gap:12px;padding:10px 12px;border-radius:14px;background:#f8fafc;">
                        <span style="color:#64748b;font-size:12px;font-weight:800;">{{ $row['label'] }}</span>
                        <span style="color:#111827;font-size:13px;font-weight:800;">{{ $row['value'] }}</span>
                    </div>
                @empty
                    <div style="padding:12px 14px;border-radius:14px;background:#f8fafc;color:#64748b;font-size:13px;font-weight:700;">Тут зʼявляться характеристики з repeater блоку.</div>
                @endforelse
            </div>
        </div>

        <div style="display:grid;gap:12px;padding:16px;border:1px solid #e2e8f0;border-radius:20px;background:#fff;">
            <h4 style="margin:0;font-size:17px;color:#111827;">Блок “Про збірку”</h4>
            @foreach ((array) ($about['intro'] ?? []) as $paragraph)
                <p style="margin:0;color:#334155;font-size:14px;line-height:1.65;font-weight:800;">{{ $paragraph }}</p>
            @endforeach

            @if (!empty($about['notes']))
                <div style="display:grid;gap:6px;">
                    @foreach ((array) $about['notes'] as $note)
                        <p style="margin:0;color:#111827;font-size:14px;font-weight:800;line-height:1.55;">{{ $note }}</p>
                    @endforeach
                </div>
            @endif

            @foreach ([
                $about['setup_title'] ?? null => $about['setup_items'] ?? [],
                $about['delivery_title'] ?? null => array_merge((array) ($about['delivery_items'] ?? []), (array) ($about['delivery_steps'] ?? [])),
                $about['warranty_title'] ?? null => $about['warranty_items'] ?? [],
            ] as $title => $items)
                @if (!empty($title) || !empty($items))
                    <div style="display:grid;gap:8px;">
                        @if (!empty($title))
                            <strong style="font-size:15px;color:#111827;">{{ $title }}</strong>
                        @endif
                        @foreach ((array) $items as $item)
                            <div style="display:flex;gap:10px;color:#334155;font-size:14px;font-weight:700;line-height:1.55;">
                                <span style="color:{{ $tone['from'] }};">•</span>
                                <span>{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
