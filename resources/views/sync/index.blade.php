<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Google Sheets Sync') }}
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

            @if (!$isConfigured)
                <div class="mb-4 rounded-md bg-amber-50 p-4 border border-amber-200">
                    <p class="text-sm text-amber-800 font-medium">{{ __('Warning') }}</p>
                    <p class="text-sm text-amber-700 mt-1">{{ __('Google Sheets is not configured. Set GOOGLE_SHEET_ID and GOOGLE_SERVICE_ACCOUNT_JSON in your .env file to enable sync.') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Persons sync status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Persons') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Last synced') }}:</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">
                            {{ $lastPersonSync ? \Carbon\Carbon::parse($lastPersonSync)->format('M d, Y H:i') : __('Never') }}
                        </p>
                    </div>
                </div>

                <!-- Training Sessions sync status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Training Sessions') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Last synced') }}:</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">
                            {{ $lastSessionSync ? \Carbon\Carbon::parse($lastSessionSync)->format('M d, Y H:i') : __('Never') }}
                        </p>
                    </div>
                </div>

                <!-- Debates sync status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Debates') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Last synced') }}:</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">
                            {{ $lastDebateSync ? \Carbon\Carbon::parse($lastDebateSync)->format('M d, Y H:i') : __('Never') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <form action="{{ route('sync.pull') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        @if (!$isConfigured) disabled @endif
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ __('Pull from Google Sheets') }}
                    </button>
                </form>
                <form action="{{ route('sync.push') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        @if (!$isConfigured) disabled @endif
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ __('Push to Google Sheets') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
