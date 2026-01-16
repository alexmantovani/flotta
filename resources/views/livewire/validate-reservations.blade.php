<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (count($groupedReservations) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Periodo
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Conducente
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Veicolo
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Azioni
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($groupedReservations as $index => $group)
                        @php
                            $startDate = $group['start_date'];
                            $endDate = $group['end_date'];
                            $days = count($group['reservation_ids']);
                            $groupKey = implode('-', $group['reservation_ids']);
                        @endphp
                        <tr class="items-center">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($startDate->isSameDay($endDate))
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $startDate->locale('it')->isoFormat('DD MMMM Y') }}
                                    </div>
                                @else
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $startDate->locale('it')->isoFormat('DD MMM') }} - {{ $endDate->locale('it')->isoFormat('DD MMM Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        ({{ $days }} {{ $days == 1 ? 'giorno' : 'giorni' }})
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $group['driver']->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <select wire:model.live="selectedVehicles.{{ $groupKey }}"
                                            class="form-select block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                        @if(!isset($selectedVehicles[$groupKey]))
                                            <option value="">-- Seleziona veicolo --</option>
                                        @endif
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">
                                                {{ $vehicle->plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($group['note'])
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Note: {{ $group['note'] }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                <button type="button"
                                        wire:click="rejectReservation({{ $index }})"
                                        wire:loading.attr="disabled"
                                        wire:target="rejectReservation({{ $index }})"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                    <span wire:loading.remove wire:target="rejectReservation({{ $index }})">Rifiuta</span>
                                    <span wire:loading wire:target="rejectReservation({{ $index }})">Rifiuto...</span>
                                </button>
                                <button type="button"
                                        wire:click="approveReservation({{ $index }})"
                                        wire:loading.attr="disabled"
                                        wire:target="approveReservation({{ $index }})"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                    <span wire:loading.remove wire:target="approveReservation({{ $index }})">Approva</span>
                                    <span wire:loading wire:target="approveReservation({{ $index }})">Approvo...</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nessuna prenotazione pendente</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Tutte le prenotazioni sono state approvate o rifiutate.
            </p>
        </div>
    @endif
</div>
