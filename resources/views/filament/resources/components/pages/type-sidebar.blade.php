<style>
    .components-type-sidebar {
        display: grid;
        gap: 14px;
    }

    .components-type-sidebar__card {
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 18px 42px rgba(148, 163, 184, 0.12);
        padding: 14px;
    }

    .components-type-sidebar__list {
        display: grid;
        gap: 8px;
    }

    .components-type-sidebar__item {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        min-height: 52px;
        padding: 12px 14px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 16px;
        background: #fff;
        color: #475569;
        text-align: left;
        transition: border-color .18s ease, background-color .18s ease, box-shadow .18s ease, color .18s ease, transform .18s ease;
    }

    .components-type-sidebar__item:hover {
        border-color: rgba(245, 158, 11, 0.32);
        background: rgba(255, 251, 235, 0.72);
        color: #1e293b;
        box-shadow: 0 12px 28px rgba(245, 158, 11, 0.12);
        transform: translateY(-1px);
    }

    .components-type-sidebar__item.is-active {
        border-color: rgba(245, 158, 11, 0.42);
        background: linear-gradient(180deg, rgba(255, 251, 235, 0.96), rgba(255, 247, 237, 0.96));
        color: #111827;
        box-shadow: 0 16px 34px rgba(245, 158, 11, 0.14);
    }

    .components-type-sidebar__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: none;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(255, 247, 237, 0.95);
        color: #f59e0b;
    }

    .components-type-sidebar__item.is-active .components-type-sidebar__icon {
        background: #f59e0b;
        color: #fff;
        box-shadow: 0 12px 24px rgba(245, 158, 11, 0.24);
    }

    .components-type-sidebar__label {
        flex: 1 1 auto;
        min-width: 0;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.25;
    }

    .components-type-sidebar__badge {
        flex: none;
        min-width: 32px;
        padding: 4px 8px;
        border-radius: 999px;
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
    }

    .components-type-sidebar__item.is-active .components-type-sidebar__badge {
        background: rgba(245, 158, 11, 0.18);
        color: #92400e;
    }

    @media (min-width: 1280px) {
        .components-type-sidebar__card {
            position: sticky;
            top: 96px;
        }
    }

    @media (max-width: 1279px) {
        .components-type-sidebar__list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .components-type-sidebar__card {
            padding: 12px;
        }

        .components-type-sidebar__list {
            grid-template-columns: 1fr;
        }

        .components-type-sidebar__item {
            min-height: 50px;
            padding: 11px 12px;
        }

        .components-type-sidebar__icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
        }
    }
</style>

<div class="components-type-sidebar">
    <div class="components-type-sidebar__card">
        <div class="components-type-sidebar__list">
            @foreach ($items as $item)
                <button
                    type="button"
                    wire:key="components-type-sidebar-{{ $item['key'] }}"
                    wire:click="$set('activeTab', '{{ $item['key'] }}')"
                    @class([
                        'components-type-sidebar__item',
                        'is-active' => $item['is_active'],
                    ])
                >
                    <span class="components-type-sidebar__icon">
                        <x-filament::icon
                            :icon="$item['icon']"
                            class="h-5 w-5"
                        />
                    </span>

                    <span class="components-type-sidebar__label">
                        {{ $item['label'] }}
                    </span>

                    <span class="components-type-sidebar__badge">
                        {{ $item['badge'] }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>
</div>
