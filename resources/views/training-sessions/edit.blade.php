<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Training Session') }}
            </h2>
            <a href="{{ route('training-sessions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('training-sessions.update', $session) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $trainerIds = old('trainer_ids', $session->trainers->pluck('id')->toArray());
                            $traineeIds = old('trainee_ids', $session->trainees->pluck('id')->toArray());
                        @endphp

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="block mt-1 w-full" :value="old('title', $session->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category" :value="__('Category')" />
                            <x-text-input id="category" name="category" type="text" class="block mt-1 w-full" :value="old('category', $session->category)" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Scheduled Date -->
                        <div class="mt-4">
                            <x-input-label for="scheduled_date" :value="__('Scheduled Date')" />
                            <x-text-input id="scheduled_date" name="scheduled_date" type="date" class="block mt-1 w-full" :value="old('scheduled_date', $session->scheduled_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('scheduled_date')" class="mt-2" />
                        </div>

                        <!-- Time -->
                        <div class="mt-4">
                            <x-input-label for="time" :value="__('Time')" />
                            <x-text-input id="time" name="time" type="time" class="block mt-1 w-full" :value="old('time', $session->time)" required />
                            <x-input-error :messages="$errors->get('time')" class="mt-2" />
                        </div>

                        <!-- Duration (minutes) -->
                        <div class="mt-4">
                            <x-input-label for="duration_minutes" :value="__('Duration (minutes)')" />
                            <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="1" class="block mt-1 w-full" :value="old('duration_minutes', $session->duration_minutes)" required />
                            <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                        </div>

                        <!-- Trainers -->
                        <div class="mt-4">
                            <x-input-label :value="__('Trainers')" />
                            <div class="mt-2 space-y-2 max-h-40 overflow-y-auto border border-gray-200 rounded-md p-3">
                                @forelse ($persons as $person)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="trainer_ids[]" value="{{ $person->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($person->id, $trainerIds) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $person->full_name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">{{ __('No persons available.') }}</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('trainer_ids')" class="mt-2" />
                        </div>

                        <!-- Trainees -->
                        <div class="mt-4">
                            <x-input-label :value="__('Trainees')" />
                            <div class="mt-2 space-y-2 max-h-40 overflow-y-auto border border-gray-200 rounded-md p-3">
                                @forelse ($persons as $person)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="trainee_ids[]" value="{{ $person->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($person->id, $traineeIds) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $person->full_name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">{{ __('No persons available.') }}</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('trainee_ids')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ route('training-sessions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Session') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
