<div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </div>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            @focus="open = true"
            @input="open = true"
            placeholder="{{ __('Search everything...') }}"
            class="block w-full rounded-md border-0 py-1.5 pl-9 pr-3 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 dark:bg-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
        />
    </div>

    @if (strlen($search) >= 2)
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-2 w-full min-w-[320px] max-h-[28rem] overflow-y-auto rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-gray-600"
        >
            @if ($hasResults)
                {{-- Persons --}}
                @if (($results['persons'] ?? collect())->isNotEmpty())
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Persons') }}</h3>
                            <a href="{{ route('persons.index', ['q' => $search]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" @click="open = false">{{ __('View all') }}</a>
                        </div>
                    </div>
                    @foreach ($results['persons'] as $person)
                        <a href="{{ route('persons.show', $person) }}" class="flex items-center px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition" @click="open = false" wire:navigate>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $person->full_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $person->id }} {{ $person->contact_info ? '· ' . $person->contact_info : '' }}</p>
                            </div>
                        </a>
                    @endforeach
                @endif

                {{-- Training Sessions --}}
                @if (($results['training_sessions'] ?? collect())->isNotEmpty())
                    <div class="px-4 py-2 border-b border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Training Sessions') }}</h3>
                            <a href="{{ route('training-sessions.index', ['q' => $search]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" @click="open = false">{{ __('View all') }}</a>
                        </div>
                    </div>
                    @foreach ($results['training_sessions'] as $session)
                        <a href="{{ route('training-sessions.show', $session) }}" class="flex items-center px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition" @click="open = false" wire:navigate>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $session->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $session->id }} · {{ $session->scheduled_date->format('M d, Y') }} {{ $session->category ? '· ' . $session->category : '' }}</p>
                            </div>
                        </a>
                    @endforeach
                @endif

                {{-- Debates --}}
                @if (($results['debates'] ?? collect())->isNotEmpty())
                    <div class="px-4 py-2 border-b border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Debates') }}</h3>
                            <a href="{{ route('debates.index', ['q' => $search]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" @click="open = false">{{ __('View all') }}</a>
                        </div>
                    </div>
                    @foreach ($results['debates'] as $debate)
                        <a href="{{ route('debates.show', $debate) }}" class="flex items-center px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition" @click="open = false" wire:navigate>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $debate->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $debate->id }} · {{ ucfirst($debate->type->value) }} {{ $debate->date ? '· ' . $debate->date->format('M d, Y') : '' }}</p>
                            </div>
                        </a>
                    @endforeach
                @endif
            @else
                <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No results found for ":query"', ['query' => $search]) }}
                </div>
            @endif
        </div>
    @endif
</div>
