<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Autorizza prenotazioni pendenti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <table class="min-w-full mt-6 bg-white dark:bg-gray-800">
                        {{-- <thead>
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
                        </thead> --}}
                        <tbody>
                            @foreach ($pendingReservations as $reservation)
                               @livewire('validate-reservation-row', ['reservation' => $reservation, 'vehicles' => $vehicles])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
