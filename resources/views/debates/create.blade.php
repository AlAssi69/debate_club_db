<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Debate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('debates.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('title') border-red-500 @enderror"
                                    required autofocus>
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Type -->
                                <div>
                                    <x-input-label for="type" :value="__('Type')" />
                                    <select name="type" id="type"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('type') border-red-500 @enderror"
                                        required>
                                        <option value="friendly" {{ old('type') === 'friendly' ? 'selected' : '' }}>{{ __('Friendly') }}</option>
                                        <option value="international" {{ old('type') === 'international' ? 'selected' : '' }}>{{ __('International') }}</option>
                                        <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>{{ __('Internal') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>

                                <!-- Date -->
                                <div>
                                    <x-input-label for="date" :value="__('Date')" />
                                    <input type="date" name="date" id="date" value="{{ old('date', now()->toDateString()) }}"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('date') border-red-500 @enderror"
                                        required>
                                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <x-input-label for="location" :value="__('Location')" />
                                <input type="text" name="location" id="location" value="{{ old('location') }}"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('location') border-red-500 @enderror"
                                    placeholder="{{ __('Optional') }}">
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Outcome -->
                            <div>
                                <x-input-label for="outcome" :value="__('Outcome')" />
                                <textarea name="outcome" id="outcome" rows="4"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('outcome') border-red-500 @enderror"
                                    placeholder="{{ __('Optional') }}">{{ old('outcome') }}</textarea>
                                <x-input-error :messages="$errors->get('outcome')" class="mt-2" />
                            </div>

                            <!-- Participants -->
                            <div x-data="participantForm()">
                                <div class="flex justify-between items-center mb-2">
                                    <x-input-label :value="__('Participants')" />
                                    <button type="button" @click="addRow()"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ __('Add Participant') }}
                                    </button>
                                </div>
                                <div class="space-y-3" x-ref="participantsContainer">
                                    <template x-for="(participant, index) in participants" :key="index">
                                        <div class="flex gap-3 items-start p-3 bg-gray-50 rounded-md">
                                            <div class="flex-1">
                                                <select :name="'participants[' + index + '][person_id]'"
                                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                    <option value="">{{ __('Select person') }}</option>
                                                    @foreach ($persons as $person)
                                                        <option value="{{ $person->id }}" x-bind:selected="participant.person_id == '{{ $person->id }}'">
                                                            {{ $person->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="w-32">
                                                <select :name="'participants[' + index + '][role]'"
                                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                    <option value="debater" x-bind:selected="participant.role == 'debater'">{{ __('Debater') }}</option>
                                                    <option value="judge" x-bind:selected="participant.role == 'judge'">{{ __('Judge') }}</option>
                                                    <option value="moderator" x-bind:selected="participant.role == 'moderator'">{{ __('Moderator') }}</option>
                                                </select>
                                            </div>
                                            <button type="button" @click="removeRow(index)" x-show="participants.length > 1"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <x-input-error :messages="$errors->get('participants')" class="mt-2" />
                                @error('participants.*.person_id')
                                    <x-input-error :messages="[$message]" class="mt-2" />
                                @enderror
                                @error('participants.*.role')
                                    <x-input-error :messages="[$message]" class="mt-2" />
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ route('debates.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Debate') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function participantForm() {
            const oldParticipants = @json(old('participants', [['person_id' => '', 'role' => 'debater']]));
            return {
                participants: oldParticipants.length > 0 ? oldParticipants : [{ person_id: '', role: 'debater' }],
                addRow() {
                    this.participants.push({ person_id: '', role: 'debater' });
                },
                removeRow(index) {
                    this.participants.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
