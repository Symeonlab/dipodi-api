<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-bolt class="w-5 h-5 text-primary-500" />
                Quick Actions
            </div>
        </x-slot>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($this->getActions() as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="flex flex-col items-center gap-2 p-4 rounded-xl transition-all hover:scale-105 shadow-sm
                        @if($action['color'] === 'info') bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-300 @endif
                        @if($action['color'] === 'success') bg-green-50 hover:bg-green-100 text-green-700 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-300 @endif
                        @if($action['color'] === 'warning') bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 dark:text-amber-300 @endif
                        @if($action['color'] === 'purple') bg-purple-50 hover:bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 dark:text-purple-300 @endif
                        @if($action['color'] === 'danger') bg-red-50 hover:bg-red-100 text-red-700 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-300 @endif
                        @if($action['color'] === 'gray') bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300 @endif
                    "
                >
                    <x-dynamic-component :component="$action['icon']" class="w-8 h-8" />
                    <span class="text-sm font-semibold text-center">{{ $action['label'] }}</span>
                    @if(isset($action['description']))
                        <span class="text-xs opacity-75 text-center">{{ $action['description'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
