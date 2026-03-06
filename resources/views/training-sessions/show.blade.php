<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $session->title }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('training-sessions.edit', $session) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('attendance.edit', $session) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                    {{ __('Take Attendance') }}
                </a>
                <a href="{{ route('training-sessions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Session Details -->
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Title') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Category') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->category ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Scheduled Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->scheduled_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Time') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($session->time)->format('g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Duration') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->duration_minutes }} {{ __('minutes') }}</dd>
                        </div>
                    </dl>

                    <!-- Trainers -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('Trainers') }}</h3>
                        @if ($session->trainers->isNotEmpty())
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Attendance') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($session->trainers as $person)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $person->full_name }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if ($person->pivot->status)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $person->pivot->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($person->pivot->status) }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No trainers assigned.') }}</p>
                        @endif
                    </div>

                    <!-- Trainees -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('Trainees') }}</h3>
                        @if ($session->trainees->isNotEmpty())
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Attendance') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($session->trainees as $person)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $person->full_name }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if ($person->pivot->status)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $person->pivot->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($person->pivot->status) }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No trainees assigned.') }}</p>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('attendance.edit', $session) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Take Attendance') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
