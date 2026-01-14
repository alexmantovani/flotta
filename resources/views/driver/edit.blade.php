<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ $driver ? route('driver.show', $driver) : route('driver.index') }}"
                   class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $driver ? 'Modifica Conducente' : 'Nuovo Conducente' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informazioni Conducente
                    </h3>
                </div>

                <form action="{{ $driver ? route('driver.update', $driver) : route('driver.store') }}" method="POST" class="p-6">
                    @csrf
                    @if($driver)
                        @method('PUT')
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        Ci sono degli errori nel form:
                                    </h3>
                                    <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   value="{{ old('first_name', $driver->first_name ?? '') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('first_name') border-red-500 @enderror"
                                   placeholder="Es: Mario">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cognome -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cognome <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="last_name"
                                   id="last_name"
                                   value="{{ old('last_name', $driver->last_name ?? '') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('last_name') border-red-500 @enderror"
                                   placeholder="Es: Rossi">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Matricola -->
                        <div>
                            <label for="uuid" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Matricola
                            </label>
                            <input type="text"
                                   name="uuid"
                                   id="uuid"
                                   value="{{ old('uuid', $driver->uuid ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('uuid') border-red-500 @enderror"
                                   placeholder="Es: MAT001">
                            @error('uuid')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $driver->email ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                                   placeholder="Es: mario.rossi@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefono -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Telefono
                            </label>
                            <input type="text"
                                   name="phone_number"
                                   id="phone_number"
                                   value="{{ old('phone_number', $driver->phone_number ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('phone_number') border-red-500 @enderror"
                                   placeholder="Es: +39 123 456 789">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ $driver ? route('driver.show', $driver) : route('driver.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annulla
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $driver ? 'Salva Modifiche' : 'Crea Conducente' }}
                        </button>
                    </div>
                </form>

                <!-- Delete Driver Section (only in edit mode) -->
                @if($driver)
                    <div class="px-6 pb-6">
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                Zona Pericolosa
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Eliminare questo conducente rimuoverà permanentemente tutti i dati associati.
                            </p>
                            <form action="{{ route('driver.destroy', $driver) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo conducente? Questa azione non può essere annullata.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Elimina Conducente
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
