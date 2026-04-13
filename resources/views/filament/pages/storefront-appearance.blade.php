<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3">
            <x-filament::button type="submit">
                Зберегти
            </x-filament::button>

            <x-filament::button
                color="gray"
                tag="a"
                :href="url('/')"
                target="_blank"
            >
                Відкрити сайт
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
