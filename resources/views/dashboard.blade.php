<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">My Total Workouts</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $myProgressCount }}
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">My Latest Weight</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            @if($myLatestWeight)
                                {{ $myLatestWeight->weight }} <span class="text-lg font-normal">kg</span>
                            @else
                                <span class="text-lg font-normal">No weight logged</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="bg-blue-600 overflow-hidden shadow-sm sm:rounded-lg">
                    <a href="{{ url('/admin') }}" class="block p-6 h-full">
                        <h3 class="text-sm font-medium text-blue-200">Admin Panel</h3>
                        <p class="mt-1 text-xl font-semibold text-white">
                            Go to Admin Dashboard &rarr;
                        </p>
                    </a>
                </div>
            </div>

            @if($latestPost)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Latest from the blog</h3>
                        <h2 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $latestPost->{'title_'.app()->getLocale()} ?? $latestPost->title_en }}</h2>
                        <div class="mt-4 prose dark:prose-invert max-w-none">
                            {!! $latestPost->{'content_'.app()->getLocale()} ?? $latestPost->content_en !!}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
