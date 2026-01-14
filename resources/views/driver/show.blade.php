<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button onclick="window.history.back()"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </button>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Dettaglio Conducente
                </h2>
            </div>

            <div class="flex items-center space-x-3">
                <x-driver-status :availability="$driver->availability" />
                <a href="{{ route('driver.edit', $driver) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifica
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hero Section con Info Conducente -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div>
                                        <h1 class="text-3xl font-bold">{{ $driver->name }}</h1>
                                        <p class="text-indigo-100 text-lg">
                                            @if($driver->uuid)
                                                Matricola: {{ $driver->uuid }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-1">
                                    @if($driver->email)
                                        <div class="flex items-center text-indigo-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $driver->email }}
                                        </div>
                                    @endif
                                    @if($driver->phone_number)
                                        <div class="flex items-center text-indigo-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $driver->phone_number }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($currentReservation)
                                <div class="flex space-x-2">
                                    <a href="{{ route('reservation.show', $currentReservation) }}"
                                        class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 text-sm font-medium rounded-lg hover:bg-indigo-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Prenotazione Attuale
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiche Card -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @php
                    $totalReservations = $upcomingReservations->flatten()->count();
                    $nextReservation = $upcomingReservations->flatten()->first();
                @endphp

                <!-- Prenotazioni Future -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Prenotazioni Future</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalReservations }}</p>
                        </div>
                    </div>
                </div>

                <!-- Prossima Prenotazione -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Prossima Prenotazione</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                @if ($nextReservation)
                                    {{ $nextReservation->date->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">Nessuna</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Veicolo Corrente -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Veicolo Attuale</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                @if ($currentReservation)
                                    {{ $currentReservation->vehicle->plate }}
                                @else
                                    <span class="text-gray-400">Nessuno</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stato Disponibilità -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full
                            @if ($driver->availability === 'available') bg-green-100 dark:bg-green-900
                            @else bg-blue-100 dark:bg-blue-900 @endif">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6
                                @if ($driver->availability === 'available') text-green-600 dark:text-green-400
                                @else text-blue-600 dark:text-blue-400 @endif"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stato</p>
                            <p
                                class="text-lg font-semibold
                                @if ($driver->availability === 'available') text-green-600 dark:text-green-400
                                @else text-blue-600 dark:text-blue-400 @endif">
                                {{ $driver->availability === 'available' ? 'Disponibile' : 'Assegnato' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Principale: Prenotazioni Future e Storico -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Sezione Prenotazioni Future (2/3) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Prenotazioni Future
                                </h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $totalReservations }}
                                    totali</span>
                            </div>
                        </div>

                        <div class="overflow-y-auto max-h-[600px]">
                            @if ($upcomingReservations->isNotEmpty())
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($upcomingReservations as $month => $reservations)
                                            <tr class="bg-gray-50 dark:bg-gray-900">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white uppercase text-sm"
                                                    colspan="3">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('it')->isoFormat('MMMM Y') }}
                                                    </div>
                                                </td>
                                            </tr>

                                            @foreach ($reservations as $reservation)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div
                                                                class="flex-shrink-0 h-9 w-9 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900 rounded-full">
                                                                <span
                                                                    class="text-indigo-700 dark:text-indigo-300 font-bold">{{ $reservation->date->format('d') }}</span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div
                                                                    class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $reservation->date->translatedFormat('l') }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                    {{ $reservation->date->format('d/m/Y') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-5 w-5 mr-2 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $reservation->vehicle->plate }}</div>
                                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                                        <x-reservation-status :status="$reservation->status" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="p-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400">Nessuna prenotazione futura per
                                        questo conducente</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sezione Assegnazione Corrente e Storico (1/3) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Attività
                            </h3>
                        </div>

                        <div class="overflow-y-auto max-h-[600px] p-6">
                            <!-- Assegnazione Corrente -->
                            @if ($currentReservation)
                                <div class="mb-6">
                                    <h4
                                        class="text-sm font-bold text-blue-700 dark:text-blue-300 mb-3 uppercase tracking-wide">
                                        Assegnazione Corrente
                                    </h4>
                                    <div
                                        class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $currentReservation->vehicle->brand }} {{ $currentReservation->vehicle->model }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            {{ $currentReservation->vehicle->plate }}
                                        </div>
                                        <div class="mt-3">
                                            <x-reservation-status :status="$currentReservation->status" />
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Storico Recente -->
                            @if ($pastReservations->isNotEmpty())
                                <div>
                                    <h4
                                        class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-3 uppercase tracking-wide">
                                        Cronologia Recente
                                    </h4>
                                    @foreach ($pastReservations as $reservation)
                                        <div
                                            class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 mb-2 hover:bg-gray-100 dark:hover:bg-gray-900/50 transition">
                                            <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                                {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-between">
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $reservation->date->format('d/m/Y') }}
                                                </span>
                                                <span class="text-xs text-gray-700 dark:text-gray-300">
                                                    {{ $reservation->vehicle->plate }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Nessuna attività -->
                            @if (!$currentReservation && $pastReservations->isEmpty())
                                <div class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm">Nessuna attività
                                        registrata</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
