<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Training Sessions') }}
        </h2>
        <a href="{{ route('training-sessions.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            {{ __('Add Session') }}
        </a>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/50 p-4">
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                {{-- Search & Filters Toolbar --}}
                <div class="mb-6 space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <x-search-input
                                wire:model.live.debounce.300ms="search"
                                placeholder="{{ __('Search by title, category, or ID...') }}"
                            />
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <input type="text" wire:model.live.debounce.300ms="category" placeholder="{{ __('Category') }}" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6 w-36">
                            <input type="date" wire:model.live="dateFrom" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" title="{{ __('Date from') }}">
                            <input type="date" wire:model.live="dateTo" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" title="{{ __('Date to') }}">
                            @if ($search || $category || $dateFrom || $dateTo)
                                <button wire:click="resetFilters" type="button" class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                    {{ __('Reset') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <x-sort-header column="title" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Title') }}
                                </x-sort-header>
                                <x-sort-header column="category" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Category') }}
                                </x-sort-header>
                                <x-sort-header column="scheduled_date" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Date') }}
                                </x-sort-header>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Time') }}
                                </th>
                                <x-sort-header column="duration_minutes" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Duration') }}
                                </x-sort-header>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Trainers') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Trainees') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($sessions as $session)
                                <tr wire:key="session-{{ $session->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('training-sessions.show', $session) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">
                                            {{ $session->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $session->category ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $session->scheduled_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($session->time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $session->duration_minutes }} min
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $session->trainers->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $session->trainees->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('training-sessions.show', $session) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">{{ __('View') }}</a>
                                        <a href="{{ route('training-sessions.edit', $session) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">{{ __('Edit') }}</a>
                                        <form action="{{ route('training-sessions.destroy', $session) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this session?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No training sessions found.') }}
                                        <a href="{{ route('training-sessions.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 ml-1">{{ __('Add one') }}</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sessions->hasPages())
                    <div class="mt-4">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
