<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-signal class="w-5 h-5 text-primary-500" />
                Intensity Zones
            </div>
        </x-slot>

        <div class="flex flex-wrap gap-3">
            @foreach($this->getZones() as $zone)
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                    <span class="w-4 h-4 rounded-full {{ $zone['bg'] }} shrink-0"></span>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $zone['label'] }}</span>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $zone['range'] }} &middot; {{ $zone['rpe'] }}</span>
                    </div>
                    @if($zone['count'] > 0)
                        <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold rounded-full {{ $zone['bg'] }} text-white">
                            {{ $zone['count'] }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
