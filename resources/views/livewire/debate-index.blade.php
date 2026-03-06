<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Debates') }}
        </h2>
        <a href="{{ route('debates.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            {{ __('Add Debate') }}
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
        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/50 p-4">
                <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
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
                                placeholder="{{ __('Search by title, location, or ID...') }}"
                            />
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <select wire:model.live="type" class="rounded-md border-0 py-2 pl-3 pr-8 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="">{{ __('All Types') }}</option>
                                @foreach ($debateTypes as $debateType)
                                    <option value="{{ $debateType->value }}">{{ ucfirst($debateType->value) }}</option>
                                @endforeach
                            </select>
                            <input type="date" wire:model.live="dateFrom" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" title="{{ __('Date from') }}">
                            <input type="date" wire:model.live="dateTo" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" title="{{ __('Date to') }}">
                            @if ($search || $type || $dateFrom || $dateTo)
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
                                <x-sort-header column="type" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Type') }}
                                </x-sort-header>
                                <x-sort-header column="date" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Date') }}
                                </x-sort-header>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Location') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Participants') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($debates as $debate)
                                <tr wire:key="debate-{{ $debate->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('debates.show', $debate) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">
                                            {{ $debate->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $typeBadgeClasses = match ($debate->type->value) {
                                                'friendly' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
                                                'international' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
                                                'internal' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300',
                                                default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeBadgeClasses }}">
                                            {{ ucfirst($debate->type->value) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $debate->date?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $debate->location ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $debate->participants->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('debates.show', $debate) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">{{ __('View') }}</a>
                                        <a href="{{ route('debates.edit', $debate) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">{{ __('Edit') }}</a>
                                        <form action="{{ route('debates.destroy', $debate) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this debate?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No debates found.') }}
                                        <a href="{{ route('debates.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 ml-1">{{ __('Add one') }}</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($debates->hasPages())
                    <div class="mt-4">
                        {{ $debates->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
