<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">Programme Reference Guide</h1>
            <p class="opacity-90">Complete reference for the DiPODDI 5-zone training system, theme types, and workout algorithm.</p>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning-500">{{ $stats['total_themes'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Workout Themes</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary-500">{{ $stats['total_exercises'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Exercises</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-500">{{ $stats['total_profiles'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Player Profiles</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success-500">{{ $stats['total_zones'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Intensity Zones</div>
                </div>
            </x-filament::section>
        </div>

        {{-- 5-Zone System Legend --}}
        <x-filament::section heading="5-Zone Intensity System" icon="heroicon-o-signal">
            <div class="space-y-4">
                {{-- Visual zone bar --}}
                <div class="flex rounded-xl overflow-hidden h-12 shadow-sm">
                    <div class="flex-1 bg-blue-500 flex items-center justify-center text-white text-xs font-bold">50-60%</div>
                    <div class="flex-1 bg-green-500 flex items-center justify-center text-white text-xs font-bold">60-70%</div>
                    <div class="flex-1 bg-yellow-500 flex items-center justify-center text-white text-xs font-bold">70-80%</div>
                    <div class="flex-1 bg-orange-500 flex items-center justify-center text-white text-xs font-bold">80-90%</div>
                    <div class="flex-1 bg-red-500 flex items-center justify-center text-white text-xs font-bold">90-100%</div>
                </div>

                {{-- Zone detail cards --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($zones as $zone)
                        <div class="{{ $zone['bg_light'] }} rounded-xl p-4 border-l-4 border-{{ $zone['color'] }}-500">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-4 h-4 rounded-full {{ $zone['bg_class'] }}"></span>
                                <span class="font-bold text-sm {{ $zone['text_class'] }}">{{ $zone['range'] }}</span>
                            </div>
                            <h4 class="font-semibold text-sm {{ $zone['text_class'] }} mb-1">{{ $zone['label'] }}</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $zone['description'] }}</p>
                            @if(isset($stats['themes_by_zone'][$zone['color']]))
                                <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-xs font-semibold {{ $zone['text_class'] }}">
                                        {{ $stats['themes_by_zone'][$zone['color']] }} {{ Str::plural('theme', $stats['themes_by_zone'][$zone['color']]) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </x-filament::section>

        {{-- Theme Types Reference --}}
        <x-filament::section heading="Theme Types Reference" icon="heroicon-o-squares-2x2" collapsible>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($themeTypes as $theme)
                    <div class="rounded-xl p-5 bg-gray-50 dark:bg-gray-800/50 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                @if($theme['color'] === 'primary') bg-primary-100 dark:bg-primary-900/30 @endif
                                @if($theme['color'] === 'danger') bg-red-100 dark:bg-red-900/30 @endif
                                @if($theme['color'] === 'warning') bg-amber-100 dark:bg-amber-900/30 @endif
                                @if($theme['color'] === 'success') bg-green-100 dark:bg-green-900/30 @endif
                                @if($theme['color'] === 'info') bg-blue-100 dark:bg-blue-900/30 @endif
                            ">
                                <x-dynamic-component :component="$theme['icon']" class="w-6 h-6
                                    @if($theme['color'] === 'primary') text-primary-500 @endif
                                    @if($theme['color'] === 'danger') text-red-500 @endif
                                    @if($theme['color'] === 'warning') text-amber-500 @endif
                                    @if($theme['color'] === 'success') text-green-500 @endif
                                    @if($theme['color'] === 'info') text-blue-500 @endif
                                " />
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $theme['label'] }}</h4>
                                <span class="text-xs font-mono text-gray-500">{{ $theme['type'] }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $theme['description'] }}</p>
                        <div class="text-xs text-gray-500 dark:text-gray-500">
                            <span class="font-semibold">Examples:</span> {{ $theme['examples'] }}
                        </div>
                        @if(isset($stats['themes_by_type'][$theme['type']]))
                            <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($theme['color'] === 'primary') bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 @endif
                                    @if($theme['color'] === 'danger') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 @endif
                                    @if($theme['color'] === 'warning') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 @endif
                                    @if($theme['color'] === 'success') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 @endif
                                    @if($theme['color'] === 'info') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 @endif
                                ">
                                    {{ $stats['themes_by_type'][$theme['type']] }} {{ Str::plural('theme', $stats['themes_by_type'][$theme['type']]) }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        {{-- Themes by Zone Breakdown --}}
        <x-filament::section heading="Themes Distribution by Zone" icon="heroicon-o-chart-bar" collapsible>
            <div class="space-y-3">
                @foreach($zones as $zone)
                    @php
                        $count = $stats['themes_by_zone'][$zone['color']] ?? 0;
                        $total = $stats['total_themes'] ?: 1;
                        $percentage = round(($count / $total) * 100);
                    @endphp
                    <div class="flex items-center gap-4">
                        <div class="w-28 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full {{ $zone['bg_class'] }} shrink-0"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ ucfirst($zone['color']) }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-6 overflow-hidden">
                                <div class="{{ $zone['bg_class'] }} h-6 rounded-full flex items-center justify-end pr-2 transition-all"
                                     style="width: {{ max($percentage, 5) }}%">
                                    <span class="text-xs font-bold text-white">{{ $count }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-12 text-right">
                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $percentage }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        {{-- RPE Scale Reference --}}
        <x-filament::section heading="RPE Scale Reference (Rate of Perceived Exertion)" icon="heroicon-o-chart-bar-square" collapsible>
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    The RPE scale (1-10) measures how hard a session feels to the athlete. It is used alongside intensity zones to fine-tune training load and monitor fatigue.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- RPE 1-5 --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <span class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-lg shrink-0">1</span>
                        <div>
                            <span class="font-semibold text-sm text-green-700 dark:text-green-300">Very Light</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rest level. Almost no effort.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <span class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-lg shrink-0">2</span>
                        <div>
                            <span class="font-semibold text-sm text-green-700 dark:text-green-300">Light</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Easy activity. Can hold full conversation.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <span class="w-10 h-10 rounded-full bg-green-400 text-white flex items-center justify-center font-bold text-lg shrink-0">3</span>
                        <div>
                            <span class="font-semibold text-sm text-green-700 dark:text-green-300">Moderate</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Comfortable pace. Breathing slightly elevated.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20">
                        <span class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center font-bold text-lg shrink-0">4</span>
                        <div>
                            <span class="font-semibold text-sm text-yellow-700 dark:text-yellow-300">Somewhat Hard</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Starting to feel the effort. Short sentences still possible.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20">
                        <span class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center font-bold text-lg shrink-0">5</span>
                        <div>
                            <span class="font-semibold text-sm text-yellow-700 dark:text-yellow-300">Hard</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Challenging. Conversation becomes difficult.</p>
                        </div>
                    </div>
                </div>

                {{-- RPE 6-10 --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20">
                        <span class="w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-lg shrink-0">6</span>
                        <div>
                            <span class="font-semibold text-sm text-orange-700 dark:text-orange-300">Very Hard</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Heavy breathing. Only a few words at a time.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20">
                        <span class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold text-lg shrink-0">7</span>
                        <div>
                            <span class="font-semibold text-sm text-orange-700 dark:text-orange-300">Very, Very Hard</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Near limit. Struggle to maintain pace.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-red-50 dark:bg-red-900/20">
                        <span class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center font-bold text-lg shrink-0">8</span>
                        <div>
                            <span class="font-semibold text-sm text-red-700 dark:text-red-300">Extremely Hard</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Cannot speak. Only a few more reps possible.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-red-50 dark:bg-red-900/20">
                        <span class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-lg shrink-0">9</span>
                        <div>
                            <span class="font-semibold text-sm text-red-700 dark:text-red-300">Near Maximum</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Could do 1 more rep. At the edge of failure.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-red-50 dark:bg-red-900/20">
                        <span class="w-10 h-10 rounded-full bg-red-700 text-white flex items-center justify-center font-bold text-lg shrink-0">10</span>
                        <div>
                            <span class="font-semibold text-sm text-red-700 dark:text-red-300">Maximum Effort</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Absolute max. Cannot do another rep.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RPE to Zone mapping --}}
            <div class="mt-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 text-sm">RPE to Zone Mapping</h4>
                <div class="grid grid-cols-5 gap-2 text-center text-xs">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <div class="font-bold text-blue-700 dark:text-blue-300">Zone 1</div>
                        <div class="text-gray-500 dark:text-gray-400">RPE 1-2</div>
                    </div>
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <div class="font-bold text-green-700 dark:text-green-300">Zone 2</div>
                        <div class="text-gray-500 dark:text-gray-400">RPE 3-4</div>
                    </div>
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <div class="font-bold text-yellow-700 dark:text-yellow-300">Zone 3</div>
                        <div class="text-gray-500 dark:text-gray-400">RPE 5-6</div>
                    </div>
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                        <div class="font-bold text-orange-700 dark:text-orange-300">Zone 4</div>
                        <div class="text-gray-500 dark:text-gray-400">RPE 7-8</div>
                    </div>
                    <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <div class="font-bold text-red-700 dark:text-red-300">Zone 5</div>
                        <div class="text-gray-500 dark:text-gray-400">RPE 9-10</div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- Algorithm Explanation --}}
        <x-filament::section heading="Workout Algorithm" icon="heroicon-o-cpu-chip" collapsible>
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    The DiPODDI workout generation algorithm creates personalised training sessions based on the player's profile, weighted theme assignments, and intensity zones. Here is how it works step by step:
                </p>
            </div>

            <div class="space-y-4">
                @foreach($algorithmSteps as $step)
                    <div class="flex gap-4">
                        <div class="shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold text-lg">
                                {{ $step['step'] }}
                            </div>
                        </div>
                        <div class="flex-1 pb-4 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $step['title'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $step['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Flow diagram --}}
            <div class="mt-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 text-sm">Simplified Flow</h4>
                <div class="flex flex-wrap items-center justify-center gap-2 text-sm">
                    <span class="px-3 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg font-medium">Player Profile</span>
                    <x-heroicon-o-arrow-right class="w-5 h-5 text-gray-400 hidden sm:block" />
                    <span class="text-gray-400 sm:hidden">&rarr;</span>
                    <span class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg font-medium">Weighted Themes</span>
                    <x-heroicon-o-arrow-right class="w-5 h-5 text-gray-400 hidden sm:block" />
                    <span class="text-gray-400 sm:hidden">&rarr;</span>
                    <span class="px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg font-medium">Zone Selection</span>
                    <x-heroicon-o-arrow-right class="w-5 h-5 text-gray-400 hidden sm:block" />
                    <span class="text-gray-400 sm:hidden">&rarr;</span>
                    <span class="px-3 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg font-medium">Exercise Pick</span>
                    <x-heroicon-o-arrow-right class="w-5 h-5 text-gray-400 hidden sm:block" />
                    <span class="text-gray-400 sm:hidden">&rarr;</span>
                    <span class="px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg font-medium">Apply Rules</span>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
