<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Programma Nuova Manutenzione
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('maintenance.store') }}" method="POST">
                    @csrf

                    <!-- Veicolo -->
                    <div class="mb-4">
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Veicolo *
                        </label>
                        <select name="vehicle_id"
                                id="vehicle_id"
                                required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Seleziona un veicolo</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}"
                                        {{ old('vehicle_id', $selectedVehicleId) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data Inizio *
                            </label>
                            <input type="date"
                                   name="start_date"
                                   id="start_date"
                                   value="{{ old('start_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data Fine *
                            </label>
                            <input type="date"
                                   name="end_date"
                                   id="end_date"
                                   value="{{ old('end_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>

                    <!-- Motivo e Tipo -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Motivo *
                            </label>
                            <input type="text"
                                   name="reason"
                                   id="reason"
                                   value="{{ old('reason') }}"
                                   placeholder="es. Tagliando, Cambio gomme..."
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo *
                            </label>
                            <select name="type"
                                    id="type"
                                    required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <option value="scheduled" {{ old('type') == 'scheduled' ? 'selected' : '' }}>Programmata</option>
                                <option value="unscheduled" {{ old('type') == 'unscheduled' ? 'selected' : '' }}>Non programmata</option>
                                <option value="inspection" {{ old('type') == 'inspection' ? 'selected' : '' }}>Ispezione</option>
                                <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>Riparazione</option>
                            </select>
                        </div>
                    </div>

                    <!-- Descrizione -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descrizione
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="3"
                                  placeholder="Descrizione dettagliata della manutenzione..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('description') }}</textarea>
                    </div>

                    <!-- Costo e Fornitore -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Costo Previsto (€)
                            </label>
                            <input type="number"
                                   name="cost"
                                   id="cost"
                                   value="{{ old('cost') }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Officina/Fornitore
                            </label>
                            <input type="text"
                                   name="provider"
                                   id="provider"
                                   value="{{ old('provider') }}"
                                   placeholder="Nome officina..."
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Note aggiuntive
                        </label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="3"
                                  placeholder="Note interne..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Pulsanti -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('maintenance.index') }}"
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300">
                            Annulla
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Programma Manutenzione
                        </button>
                    </div>
                </form>

                <!-- Verifica disponibilità -->
                <div id="availability-check" class="mt-4 hidden">
                    <div class="p-4 rounded-lg" id="availability-message"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const vehicleSelect = document.getElementById('vehicle_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Aggiorna min date di end_date quando cambia start_date
        startDateInput.addEventListener('change', () => {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
    </script>
</x-app-layout>
