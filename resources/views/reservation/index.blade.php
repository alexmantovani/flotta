<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Vehicle Availability') }}
                    </h3>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('vehicle.index') }}" class="mb-6">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Search by plate or driver"
                                value="{{ request('search') }}" class="form-input w-full">
                            <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-md">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </form>

                    <table class="min-w-full mt-6 bg-white dark:bg-gray-800">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">
                                    Vehicle
                                </th>
                                @foreach ($dates as $date)
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-center leading-4 text-blue-500 tracking-wider"
                                        whitespace-no-wrap>{{ $date->format('d M') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                        <div>
                                            {{ $vehicle->plate }}
                                        </div>
                                        <div>
                                            {{ $vehicle->model }}
                                        </div>

                                    </td>
                                    @foreach ($dates as $date)
                                        @php
                                            $reservation = $vehicle->getReservationForDate($date);
                                        @endphp
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 text-center">
                                            {{-- <x-car-status :reservation="$reservation"></x-car-status> --}}
                                            @if ($reservation)
                                                <div class="text-base p-2 rounded-md bg-blue-500 text-white">
                                                    {{ $reservation->driver->name }}
                                                </div>
                                            @else
                                                <p class="text-sm bg-green-500 text-white'">

                                                </p>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
