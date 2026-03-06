<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $person->full_name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('persons.edit', $person) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('persons.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
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

            <div class="space-y-6">
                <!-- Person Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Details') }}</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('First Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $person->first_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Last Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $person->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Contact Info') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $person->contact_info ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Join Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $person->join_date?->format('M d, Y') ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Roles -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Roles') }}</h3>
                        @if ($person->roles->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($person->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ ucfirst($role->name->value) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No roles assigned.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Training Sessions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Training Sessions') }}</h3>
                        @if ($person->trainingSessions->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Session') }}</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($person->trainingSessions as $session)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <a href="{{ route('training-sessions.show', $session) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $session->title }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $session->scheduled_date?->format('M d, Y') }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($session->pivot->role) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No training sessions.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Debates -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Debates') }}</h3>
                        @if ($person->debates->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Debate') }}</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($person->debates as $debate)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <a href="{{ route('debates.show', $debate) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $debate->title }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $debate->date?->format('M d, Y') }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($debate->pivot->role) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No debates.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
