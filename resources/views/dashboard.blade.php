<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Persons</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPersons }}</div>
                    <a href="{{ route('persons.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">View all &rarr;</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Training Sessions</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalSessions }}</div>
                    <a href="{{ route('training-sessions.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">View all &rarr;</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Debates</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalDebates }}</div>
                    <a href="{{ route('debates.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">View all &rarr;</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Sessions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Training Sessions</h3>
                        @forelse ($upcomingSessions as $session)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                <div>
                                    <a href="{{ route('training-sessions.show', $session) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">{{ $session->title }}</a>
                                    @if($session->category)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $session->category }}</span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">{{ $session->scheduled_date->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No upcoming sessions.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Debates -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Debates</h3>
                        @forelse ($recentDebates as $debate)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                <div>
                                    <a href="{{ route('debates.show', $debate) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">{{ $debate->title }}</a>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @switch($debate->type->value)
                                            @case('friendly') bg-green-100 text-green-800 @break
                                            @case('international') bg-blue-100 text-blue-800 @break
                                            @case('internal') bg-yellow-100 text-yellow-800 @break
                                        @endswitch
                                    ">{{ ucfirst($debate->type->value) }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $debate->date->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No debates recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
