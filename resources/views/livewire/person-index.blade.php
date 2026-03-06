<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Persons') }}
        </h2>
        <a href="{{ route('persons.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            {{ __('Add Person') }}
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
                                placeholder="{{ __('Search by name, ID, or contact...') }}"
                            />
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <select wire:model.live="role" class="rounded-md border-0 py-2 pl-3 pr-8 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="">{{ __('All Roles') }}</option>
                                @foreach ($personRoles as $personRole)
                                    <option value="{{ $personRole->value }}">{{ ucfirst($personRole->value) }}</option>
                                @endforeach
                            </select>
                            <input type="date" wire:model.live="joinDateFrom" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="{{ __('From') }}" title="{{ __('Join date from') }}">
                            <input type="date" wire:model.live="joinDateTo" class="rounded-md border-0 py-2 px-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="{{ __('To') }}" title="{{ __('Join date to') }}">
                            @if ($search || $role || $joinDateFrom || $joinDateTo)
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
                                <x-sort-header column="first_name" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Name') }}
                                </x-sort-header>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Contact') }}
                                </th>
                                <x-sort-header column="join_date" :sortBy="$sortBy" :sortDirection="$sortDirection">
                                    {{ __('Join Date') }}
                                </x-sort-header>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Roles') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($persons as $person)
                                <tr wire:key="person-{{ $person->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('persons.show', $person) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">
                                            {{ $person->full_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $person->contact_info ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $person->join_date?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($person->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300">
                                                    {{ ucfirst($role->name->value) }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('persons.show', $person) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">{{ __('View') }}</a>
                                        <a href="{{ route('persons.edit', $person) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">{{ __('Edit') }}</a>
                                        <form action="{{ route('persons.destroy', $person) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this person?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No persons found.') }}
                                        <a href="{{ route('persons.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 ml-1">{{ __('Add one') }}</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($persons->hasPages())
                    <div class="mt-4">
                        {{ $persons->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
