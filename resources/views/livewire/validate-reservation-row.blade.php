<div>
    <tr class="items-center">
            <td class="px-6 py-4 whitespace-nowrap">
                @php
                    $startDate = $reservationGroup['start_date'];
                    $endDate = $reservationGroup['end_date'];
                    $days = count($reservationGroup['reservation_ids']);
                @endphp

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
                    {{ $reservationGroup['driver']->name }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div>
                    <select wire:model.live="selectedVehicleId"
                            class="form-select block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        @if(!$selectedVehicleId)
                            <option value="">-- Seleziona veicolo --</option>
                        @endif
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($reservationGroup['note'])
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Note: {{ $reservationGroup['note'] }}
                    </div>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                <button type="button"
                        wire:click="rejectReservation()"
                        wire:loading.attr="disabled"
                        wire:target="rejectReservation"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    <span wire:loading.remove wire:target="rejectReservation">Rifiuta</span>
                    <span wire:loading wire:target="rejectReservation">Rifiuto...</span>
                </button>
                <button type="button"
                        wire:click="approveReservation()"
                        wire:loading.attr="disabled"
                        wire:target="approveReservation"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    <span wire:loading.remove wire:target="approveReservation">Approva</span>
                    <span wire:loading wire:target="approveReservation">Approvo...</span>
                </button>
            </td>
        </tr>
</div>
