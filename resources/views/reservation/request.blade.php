<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Prenota una macchina') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('reservations.store_request') }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center space-x-2">
                            <div class="mb-4 flex-1">
                                <label for="date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Data') }}</label>
                                <input type="date" name="date" id="date" class="form-input mt-1 block w-full"
                                    min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold "
                                    for="duration">
                                    {{ __('Durata') }}
                                </label>
                                <input type="number" name="duration" id="duration" class="form-input mt-1 block w-full" value="1" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="note"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Note') }}</label>
                            <textarea name="note" id="note" rows="4" class="form-textarea mt-1 block w-full"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit">
                                {{ __('Conferma') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
