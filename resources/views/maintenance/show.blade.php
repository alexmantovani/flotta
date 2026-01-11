<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dettaglio Manutenzione
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('maintenance.edit', $maintenance) }}"
                   class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                    Modifica
                </a>
                <form action="{{ route('maintenance.destroy', $maintenance) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Sei sicuro di voler eliminare questa manutenzione?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Elimina
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Veicolo -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Veicolo</h3>
                        <div class="text-gray-900 dark:text-gray-100 text-xl font-bold">
                            {{ $maintenance->vehicle->plate }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">
                            {{ $maintenance->vehicle->brand }} {{ $maintenance->vehicle->model }}
                        </div>
                        <a href="{{ route('vehicle.show', $maintenance->vehicle) }}"
                           class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                            Vedi dettagli veicolo →
                        </a>
                    </div>

                    <!-- Periodo -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Periodo</h3>
                        <div class="text-gray-900 dark:text-gray-100">
                            <strong>Dal:</strong> {{ $maintenance->start_date->format('d/m/Y') }}
                        </div>
                        <div class="text-gray-900 dark:text-gray-100">
                            <strong>Al:</strong> {{ $maintenance->end_date->format('d/m/Y') }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 mt-1">
                            Durata: {{ $maintenance->duration }} giorni
                        </div>
                    </div>

                    <!-- Motivo e Tipo -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Dettagli</h3>
                        <div class="mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Motivo:</span>
                            <span class="text-gray-900 dark:text-gray-100 font-semibold">{{ $maintenance->reason }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Tipo:</span>
                            <span class="px-2 py-1 rounded text-xs
                                @if($maintenance->type == 'scheduled') bg-blue-100 text-blue-800
                                @elseif($maintenance->type == 'unscheduled') bg-yellow-100 text-yellow-800
                                @elseif($maintenance->type == 'inspection') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($maintenance->type) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Stato:</span>
                            <span class="px-2 py-1 rounded text-xs
                                @if($maintenance->status == 'scheduled') bg-gray-100 text-gray-800
                                @elseif($maintenance->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($maintenance->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($maintenance->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Costo e Fornitore -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Informazioni Economiche</h3>
                        <div class="mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Costo:</span>
                            <span class="text-gray-900 dark:text-gray-100 font-semibold">
                                {{ $maintenance->cost ? '€ ' . number_format($maintenance->cost, 2) : 'Non specificato' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Officina:</span>
                            <span class="text-gray-900 dark:text-gray-100">
                                {{ $maintenance->provider ?? 'Non specificato' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Descrizione -->
                @if($maintenance->description)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Descrizione</h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $maintenance->description }}</p>
                    </div>
                @endif

                <!-- Note -->
                @if($maintenance->notes)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Note</h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $maintenance->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
