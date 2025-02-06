<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="font-thin text-gray-500 dark:text-gray-500">
                    Targa
                </span>
                {{ $vehicle->plate }}
            </h2>

            <div>
                <x-vehicle-availability :availability="$vehicle->availability()" />
            </div>
        </div>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-4">

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg col-span-2">
                    <div class="p-5">
                        <div class="text-xl font-semibold text-gray-700 dark:text-gray-300 py-3">
                            Prenotazioni
                        </div>

                        <table class="min-w-full">
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($groupedReservations as $month => $reservations)
                                    <tr class="bg-gray-100">
                                        <td class="px-3 py-3 font-semibold text-gray-900 uppercase border border-gray-200 dark:border-gray-700"
                                            colspan="4">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('it')->isoFormat('MMMM Y') }}
                                        </td>
                                    </tr>

                                    @foreach ($reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-3 whitespace-nowrap  ">
                                                {{ $reservation->date->format('d') }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right">
                                                {{ $reservation->driver->name }}
                                            </td>
                                            <td class="p-3 whitespace-nowrap text-right">
                                                <x-reservation-status :status="$reservation->status" class="justify-end" />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-5 h-full">
                        <div class="flex justify-between items-center">
                            <div class="text-xl font-semibold text-gray-700 dark:text-gray-300 py-3">
                                Manutenzione
                            </div>
                            <div>
                                <a href="{{ route('reservation.create', ['status' => 'maintenance', 'vehicle_id' => $vehicle->id]) }}"
                                    title="Programma manutenzione">
                                    <button
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-6">
                                            <path fill-rule="evenodd"
                                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="justify-center min-h-full text-gray-400 p-5 capitalize">
                            @if ($maintenanceReservations->isNotEmpty())
                                @foreach ($maintenanceReservations as $reservation)
                                    <div class="text-center text-lg pt-2 text-orange-500">
                                        {{ $reservation->date->translatedFormat('l j F Y') }}
                                    </div>
                                    <div class="text-center pb-2">
                                        {{ $reservation->note ?? 'nessuna nota' }}
                                    </div>
                                @endforeach
                            @else
                                Non ci sono periodi di manutenzione per questo veicolo.
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div> --}}

    <div class="pt-16 h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-[calc(100%-4rem)]">
            <div class="grid grid-cols-3 gap-4 h-full">

                <!-- Sezione Prenotazioni -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg col-span-2 flex flex-col">
                    <div class="p-5">
                        <div class="text-xl font-semibold text-gray-700 dark:text-gray-300 py-3">
                            Prenotazioni
                        </div>
                    </div>

                    <!-- Contenuto Scrollabile -->
                    <div class="flex-grow overflow-y-auto p-5">
                        <table class="min-w-full">
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($groupedReservations as $month => $reservations)
                                    <tr class="bg-gray-100">
                                        <td class="px-3 py-3 font-semibold text-gray-900 uppercase border border-gray-200 dark:border-gray-700"
                                            colspan="4">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('it')->isoFormat('MMMM Y') }}
                                        </td>
                                    </tr>

                                    @foreach ($reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                {{ $reservation->date->format('d') }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right">
                                                {{ $reservation->driver->name }}
                                            </td>
                                            <td class="p-3 whitespace-nowrap text-right">
                                                <x-reservation-status :status="$reservation->status" class="justify-end" />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sezione Manutenzione -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg flex flex-col">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div class="text-xl font-semibold text-gray-700 dark:text-gray-300 py-3">
                                Manutenzione
                            </div>
                            <div>
                                <a href="{{ route('reservation.create', ['status' => 'maintenance', 'vehicle_id' => $vehicle->id]) }}"
                                    title="Programma manutenzione">
                                    <button
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-6">
                                            <path fill-rule="evenodd"
                                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contenuto Scrollabile -->
                    <div class="flex-grow overflow-y-auto p-5 text-gray-400 ">
                        @if ($maintenanceReservations->isNotEmpty())
                            @foreach ($maintenanceReservations as $reservation)
                                <div class="text-center text-lg pt-2 text-orange-500 capitalize">
                                    {{ $reservation->date->translatedFormat('l j F Y') }}
                                </div>
                                <div class="text-center pb-2">
                                    {{ $reservation->note ?? 'nessuna nota' }}
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-center h-full text-gray-500 text-center">
                                Non ci sono periodi di manutenzione programmati per questo veicolo.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
