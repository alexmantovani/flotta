<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('vehicle.index') }}"
                   class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Dettaglio Veicolo
                </h2>
            </div>

            <div class="flex items-center space-x-3">
                <x-vehicle-availability :availability="$vehicle->availability()" />
                <a href="{{ route('vehicle.edit', $vehicle) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifica
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hero Section con Info Veicolo -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <div>
                                        <h1 class="text-3xl font-bold">{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
                                        <p class="text-indigo-100 text-lg">Targa: {{ $vehicle->plate }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('reservation.create', ['vehicle_id' => $vehicle->id]) }}"
                                   class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 text-sm font-medium rounded-lg hover:bg-indigo-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Nuova Prenotazione
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiche Card -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @php
                    $totalReservations = $groupedReservations->flatten()->count();
                    $nextReservation = $groupedReservations->flatten()->first();
                    $totalMaintenances = $upcomingMaintenances->count() + $inProgressMaintenances->count() + $pastMaintenances->count();
                @endphp

                <!-- Prenotazioni Future -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Prossima Prenotazione</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                @if($nextReservation)
                                    {{ $nextReservation->date->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">Nessuna</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Manutenzioni -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Manutenzioni Totali</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalMaintenances }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stato Veicolo -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full
                            @if($vehicle->availability() === 'available') bg-green-100 dark:bg-green-900
                            @else bg-red-100 dark:bg-red-900
                            @endif">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6
                                @if($vehicle->availability() === 'available') text-green-600 dark:text-green-400
                                @else text-red-600 dark:text-red-400
                                @endif"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stato</p>
                            <p class="text-lg font-semibold
                                @if($vehicle->availability() === 'available') text-green-600 dark:text-green-400
                                @else text-red-600 dark:text-red-400
                                @endif">
                                {{ $vehicle->availability() === 'available' ? 'Disponibile' : 'Non Disponibile' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Principale: Prenotazioni e Manutenzioni -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Sezione Prenotazioni (2/3) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Prenotazioni Future
                                </h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $totalReservations }} totali</span>
                            </div>
                        </div>

                        <div class="overflow-y-auto max-h-[600px]">
                            @if($groupedReservations->isNotEmpty())
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($groupedReservations as $month => $reservations)
                                            <tr class="bg-gray-50 dark:bg-gray-900">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white uppercase text-sm" colspan="4">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('it')->isoFormat('MMMM Y') }}
                                                    </div>
                                                </td>
                                            </tr>

                                            @foreach ($reservations as $reservation)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900 rounded-full">
                                                                <span class="text-indigo-700 dark:text-indigo-300 font-bold">{{ $reservation->date->format('d') }}</span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $reservation->date->translatedFormat('l') }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                    {{ $reservation->date->format('d/m/Y') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            <div class="text-sm text-gray-900 dark:text-white">{{ $reservation->driver->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                                        <x-reservation-status :status="$reservation->status" />
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('reservation.show', $reservation) }}"
                                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                            Dettagli
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="p-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400">Nessuna prenotazione futura per questo veicolo</p>
                                    <a href="{{ route('reservation.create', ['vehicle_id' => $vehicle->id]) }}"
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                        Crea Prenotazione
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sezione Manutenzioni (1/3) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Manutenzioni
                                </h3>
                                <a href="{{ route('maintenance.create', ['vehicle_id' => $vehicle->id]) }}"
                                   title="Programma manutenzione"
                                   class="text-orange-600 hover:text-orange-700 dark:text-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="overflow-y-auto max-h-[600px] p-6">
                            <!-- Manutenzioni in corso -->
                            @if($inProgressMaintenances->isNotEmpty())
                                <div class="mb-6">
                                    <h4 class="text-sm font-bold text-orange-700 dark:text-orange-300 mb-3 uppercase tracking-wide">
                                        In Corso
                                    </h4>
                                    @foreach($inProgressMaintenances as $maintenance)
                                        <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 rounded-lg p-4 mb-3 hover:shadow-md transition">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $maintenance->reason }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $maintenance->start_date->format('d/m/Y') }} - {{ $maintenance->end_date->format('d/m/Y') }}
                                            </div>
                                            @if($maintenance->description)
                                                <div class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                                    {{ $maintenance->description }}
                                                </div>
                                            @endif
                                            <div class="mt-3 flex space-x-2">
                                                <a href="{{ route('maintenance.show', $maintenance) }}"
                                                   class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
                                                    Dettagli →
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Manutenzioni future -->
                            @if($upcomingMaintenances->isNotEmpty())
                                <div class="mb-6">
                                    <h4 class="text-sm font-bold text-blue-700 dark:text-blue-300 mb-3 uppercase tracking-wide">
                                        Programmate
                                    </h4>
                                    @foreach($upcomingMaintenances as $maintenance)
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg p-4 mb-3 hover:shadow-md transition">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $maintenance->reason }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $maintenance->start_date->format('d/m/Y') }} - {{ $maintenance->end_date->format('d/m/Y') }}
                                                <span class="text-xs ml-2 italic">
                                                    ({{ $maintenance->start_date->diffForHumans() }})
                                                </span>
                                            </div>
                                            @if($maintenance->provider)
                                                <div class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                                    Officina: <span class="font-medium">{{ $maintenance->provider }}</span>
                                                </div>
                                            @endif
                                            <div class="mt-3 flex space-x-2">
                                                <a href="{{ route('maintenance.edit', $maintenance) }}"
                                                   class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                                                    Modifica
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('maintenance.show', $maintenance) }}"
                                                   class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
                                                    Dettagli →
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Storico (ultime manutenzioni) -->
                            @if($pastMaintenances->isNotEmpty())
                                <div>
                                    <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-3 uppercase tracking-wide">
                                        Storico Recente
                                    </h4>
                                    @foreach($pastMaintenances as $maintenance)
                                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3 mb-2 hover:bg-gray-100 dark:hover:bg-gray-900/50 transition">
                                            <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                                {{ $maintenance->reason }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-between">
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $maintenance->end_date->format('d/m/Y') }}
                                                </span>
                                                @if($maintenance->cost)
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">
                                                        € {{ number_format($maintenance->cost, 2) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Nessuna manutenzione -->
                            @if($upcomingMaintenances->isEmpty() && $inProgressMaintenances->isEmpty() && $pastMaintenances->isEmpty())
                                <div class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm">Nessuna manutenzione registrata</p>
                                    <a href="{{ route('maintenance.create', ['vehicle_id' => $vehicle->id]) }}"
                                       class="mt-4 inline-flex items-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-lg transition">
                                        Programma Manutenzione
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
