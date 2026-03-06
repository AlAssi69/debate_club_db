<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Take Attendance') }} — {{ $session->title }}
            </h2>
            <a href="{{ route('training-sessions.show', $session) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/50 p-4">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/50 p-4">
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $errors->first() }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($session->participants->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No participants assigned to this session.') }}</p>
                        <a href="{{ route('training-sessions.edit', $session) }}" class="mt-4 inline-block text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                            {{ __('Edit session to add participants') }}
                        </a>
                    @else
                        <form action="{{ route('attendance.update', $session) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                {{ __('Role') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                {{ __('Status') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($session->participants as $index => $person)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $person->full_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst($person->pivot->role) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $currentStatus = $person->pivot->status ?? old("attendance.{$index}.status");
                                                        $isPresent = $currentStatus === 'present';
                                                    @endphp
                                                    <div class="flex gap-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="attendance[{{ $index }}][status]" value="present" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                                {{ $isPresent ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Present') }}</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="attendance[{{ $index }}][status]" value="absent" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                                {{ ! $isPresent ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Absent') }}</span>
                                                        </label>
                                                    </div>
                                                    <input type="hidden" name="attendance[{{ $index }}][person_id]" value="{{ $person->id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                <x-primary-button>
                                    {{ __('Save Attendance') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
