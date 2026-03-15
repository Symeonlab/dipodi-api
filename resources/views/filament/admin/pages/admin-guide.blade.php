<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">{{ __('admin.guide.welcome') }}</h1>
            <p class="opacity-90">{{ __('admin.guide.description') }}</p>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary-500">{{ \App\Models\Exercise::count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.stats.exercises') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success-500">{{ \App\Models\NutritionAdvice::count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.stats.nutrition_advice') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-500">{{ \App\Models\PlayerProfile::count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.stats.player_profiles') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning-500">{{ \App\Models\WorkoutTheme::count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.stats.workout_themes') }}</div>
                </div>
            </x-filament::section>
        </div>

        {{-- Visual Workflow Section --}}
        <x-filament::section :heading="__('admin.guide.workflow_title')" icon="heroicon-o-arrows-right-left">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Step 1 --}}
                <div class="relative">
                    <div class="bg-primary-100 dark:bg-primary-900/30 rounded-xl p-6 h-full">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-primary-500 text-white rounded-full flex items-center justify-center font-bold text-lg">1</div>
                            <h3 class="font-semibold text-primary-700 dark:text-primary-300">{{ __('admin.guide.workflow_step1_title') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.guide.workflow_step1_desc') }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-primary-200 dark:bg-primary-800 rounded text-xs">{{ __('admin.categories.musculation') }}</span>
                            <span class="px-2 py-1 bg-primary-200 dark:bg-primary-800 rounded text-xs">{{ __('admin.categories.cardio') }}</span>
                            <span class="px-2 py-1 bg-primary-200 dark:bg-primary-800 rounded text-xs">{{ __('admin.categories.kine') }}</span>
                        </div>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                        <x-heroicon-o-arrow-right class="w-6 h-6 text-gray-400" />
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative">
                    <div class="bg-success-100 dark:bg-success-900/30 rounded-xl p-6 h-full">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-success-500 text-white rounded-full flex items-center justify-center font-bold text-lg">2</div>
                            <h3 class="font-semibold text-success-700 dark:text-success-300">{{ __('admin.guide.workflow_step2_title') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.guide.workflow_step2_desc') }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-success-200 dark:bg-success-800 rounded text-xs">{{ __('admin.categories.profiles') }}</span>
                            <span class="px-2 py-1 bg-success-200 dark:bg-success-800 rounded text-xs">{{ __('admin.categories.themes') }}</span>
                        </div>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                        <x-heroicon-o-arrow-right class="w-6 h-6 text-gray-400" />
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative">
                    <div class="bg-purple-100 dark:bg-purple-900/30 rounded-xl p-6 h-full">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold text-lg">3</div>
                            <h3 class="font-semibold text-purple-700 dark:text-purple-300">{{ __('admin.guide.workflow_step3_title') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('admin.guide.workflow_step3_desc') }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-purple-200 dark:bg-purple-800 rounded text-xs">{{ __('admin.categories.nutrition') }}</span>
                            <span class="px-2 py-1 bg-purple-200 dark:bg-purple-800 rounded text-xs">{{ __('admin.categories.advice') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- Guide Sections --}}
        @foreach($sections as $section)
            <x-filament::section
                :heading="$section['title']"
                :icon="$section['icon']"
                collapsible
            >
                {{-- Steps --}}
                <div class="space-y-3 mb-4">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300">{{ __('admin.guide.steps') }}:</h4>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600 dark:text-gray-400">
                        @foreach($section['steps'] as $step)
                            @if(str_starts_with($step, '  -'))
                                <li class="ml-6 list-disc">{!! \Illuminate\Support\Str::markdown(trim(substr($step, 3))) !!}</li>
                            @elseif(str_starts_with($step, '```'))
                                <li class="ml-4"><code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-sm font-mono">{{ trim(str_replace('```', '', $step)) }}</code></li>
                            @elseif(trim($step) === '')
                                {{-- Skip empty lines --}}
                            @else
                                <li>{!! \Illuminate\Support\Str::markdown($step) !!}</li>
                            @endif
                        @endforeach
                    </ol>
                </div>

                {{-- Tips --}}
                @if(!empty($section['tips']))
                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 mt-4">
                        <h4 class="font-semibold text-amber-700 dark:text-amber-400 mb-2 flex items-center gap-2">
                            <x-heroicon-o-light-bulb class="w-5 h-5" />
                            {{ __('admin.guide.tips') }}
                        </h4>
                        <ul class="list-disc list-inside space-y-1 text-amber-600 dark:text-amber-300 text-sm">
                            @foreach($section['tips'] as $tip)
                                <li>{!! \Illuminate\Support\Str::markdown($tip) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </x-filament::section>
        @endforeach

        {{-- Quick Links --}}
        <x-filament::section :heading="__('admin.guide.quick_links')" icon="heroicon-o-link">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($quickLinks as $link)
                    <a href="{{ $link['url'] }}" class="flex flex-col items-center p-4 rounded-lg bg-{{ $link['color'] }}-50 dark:bg-{{ $link['color'] }}-900/20 hover:bg-{{ $link['color'] }}-100 dark:hover:bg-{{ $link['color'] }}-900/40 transition-colors">
                        <x-dynamic-component :component="$link['icon']" class="w-8 h-8 text-{{ $link['color'] }}-500 mb-2" />
                        <span class="text-sm font-medium text-{{ $link['color'] }}-700 dark:text-{{ $link['color'] }}-300 text-center">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </x-filament::section>

        {{-- Category Reference Card --}}
        <x-filament::section :heading="__('admin.guide.categories_reference')" icon="heroicon-o-tag" collapsible collapsed>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Exercise Categories --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
                        <x-heroicon-o-play-circle class="w-5 h-5" />
                        {{ __('admin.guide.exercise_categories') }}
                    </h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <strong>MUSCULATION:</strong> {{ __('admin.guide.cat_musculation') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <strong>BONUS:</strong> {{ __('admin.guide.cat_bonus') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                            <strong>MAISON:</strong> {{ __('admin.guide.cat_maison') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-orange-500 rounded-full"></span>
                            <strong>KINE:</strong> {{ __('admin.guide.cat_kine') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <strong>CARDIO:</strong> {{ __('admin.guide.cat_cardio') }}
                        </li>
                    </ul>
                </div>

                {{-- Player Groups --}}
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <h4 class="font-semibold text-green-700 dark:text-green-300 mb-3 flex items-center gap-2">
                        <x-heroicon-o-user-group class="w-5 h-5" />
                        {{ __('admin.guide.player_groups') }}
                    </h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                            <strong>GARDIEN:</strong> {{ __('admin.guide.group_gardien') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <strong>DÉFENSEUR:</strong> {{ __('admin.guide.group_defenseur') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <strong>MILIEU:</strong> {{ __('admin.guide.group_milieu') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <strong>ATTAQUANT:</strong> {{ __('admin.guide.group_attaquant') }}
                        </li>
                    </ul>
                </div>

                {{-- Fitness Groups --}}
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                    <h4 class="font-semibold text-purple-700 dark:text-purple-300 mb-3 flex items-center gap-2">
                        <x-heroicon-o-heart class="w-5 h-5" />
                        {{ __('admin.guide.fitness_groups') }}
                    </h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-pink-500 rounded-full"></span>
                            <strong>FITNESS_FEMME:</strong> {{ __('admin.guide.group_fitness_femme') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <strong>FITNESS_HOMME:</strong> {{ __('admin.guide.group_fitness_homme') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-orange-500 rounded-full"></span>
                            <strong>PADEL:</strong> {{ __('admin.guide.group_padel') }}
                        </li>
                    </ul>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
