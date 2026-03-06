<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $debate->title }}
            </h2>
            <a href="{{ route('debates.edit', $debate) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:ring-offset-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Edit') }}
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
                <div class="p-6 space-y-6">
                    <!-- Debate details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</dt>
                            @php
                                $typeBadgeClasses = match ($debate->type->value) {
                                    'friendly' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'international' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'internal' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                };
                            @endphp
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeBadgeClasses }}">
                                    {{ ucfirst($debate->type->value) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $debate->date?->format('M d, Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $debate->location ?? '—' }}</dd>
                        </div>
                    </div>

                    @if ($debate->outcome)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Outcome') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $debate->outcome }}</dd>
                        </div>
                    @endif

                    <!-- Participants -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Participants') }}</dt>
                        <dd>
                            @if ($debate->participants->isEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No participants.') }}</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach ($debate->participants as $participant)
                                        @php
                                            $roleBadgeClasses = match ($participant->pivot->role) {
                                                'debater' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                                'judge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                                'moderator' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <li class="flex items-center gap-2">
                                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $participant->full_name }}</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleBadgeClasses }}">
                                                {{ ucfirst($participant->pivot->role) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </dd>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('debates.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Back') }}
                        </a>
                        <a href="{{ route('debates.edit', $debate) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:ring-offset-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
