<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reservations Overview') }}
        </h2>
    </x-slot> --}}

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="py-12 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow flex">

            <!-- Colonna sinistra: Calendario -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 w-2/3">
                <div id="calendar"></div>
            </div>
            <!-- Colonna destra: Elenco prenotazioni -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 w-1/3 ml-4">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Prenotazioni per il ') }}<span id="selected-date"></span>
                </h3>
                <div id="reservations-list" class="mt-4">
                    <!-- Contenuto aggiornato dinamicamente -->
                </div>
            </div>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-4">

            {{-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6"> --}}
            <div class=" text-xl font-semibold text-gray-700 dark:text-gray-300 pt-5">
                Manutenzione programmata <span class="text-gray-400 font-thin">(per i prossimi 2 mesi)</span>
            </div>

            {{-- <div class="py-3 flex flex-wrap space-x-3 space-y-3">
                    @for ($i = 0; $i < 22; $i++)

                    @foreach ($vehiclesInMaintenance as $item)
                        <div class="py-2 rounded-lg bg-red-500 p-3 w-48">
                            <div class="text-white dark:text-gray-300 text-lg font-semibold">
                                {{ $item->first()->vehicle->plate }}
                            </div>
                            <div class="text-sm text-gray-100">
                                {{ $item->first()->vehicle->brand }} {{ $item->first()->vehicle->model }}
                            </div>
                            <div class="text-sm text-gray-200" title="{{ $item->first()->date->format('d/m/Y') }}">
                                {{ $item->first()->date->diffForHumans(['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]) }}
                            </div>
                        </div>
                    @endforeach
                    @endfor
                </div> --}}
            <div class="py-3 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach ($vehiclesInMaintenance as $item)
                    <a href="{{ route('vehicle.show', $item->first()->vehicle->id) }}">
                        <div class="rounded-lg bg-red-500 p-3">
                            <div class="text-xl text-gray-50 dark:text-gray-300 font-semibold">
                                {{ $item->first()->vehicle->plate }}
                            </div>
                            <div class="text-sm text-gray-50 uppercase">
                                @if ($item->first()->note)
                                    {{ $item->first()->note }}
                                @else
                                    {{ $item->first()->vehicle->brand }} {{ $item->first()->vehicle->model }}
                                @endif
                            </div>
                            <div class="text-sm text-red-200" title="{{ $item->first()->date->format('d/m/Y') }}">
                                {{ $item->first()->date->diffForHumans(['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- </div> --}}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                unselectAuto: false,
                select: function(info) {
                    var selectedDate = info.startStr;
                    updateReservationsList(selectedDate);
                },
                locale: 'it',
                headerToolbar: {
                    left: 'prev,next', // Togli il tasto 'today'
                    center: 'title',
                    right: ''
                }
                // validRange: {
                //     start: new Date().toISOString().split("T")[0] // Non permettere la selezione di date antecedenti ad oggi
                // },
                // datesSet: function(info) {
                //     var currentDate = calendar.getDate(); // Data attuale della vista
                //     var currentDateStr = currentDate.toISOString().split('T')[0]; // Formattazione ISO
                //     updateReservationsList(currentDateStr);
                // }
            });
            calendar.render();

            // Carica le prenotazioni per la data corrente all'avvio
            updateReservationsList(new Date().toISOString().split('T')[0]);
        });

        function updateReservationsList(date) {
            $('#selected-date').text(new Date(date).toLocaleDateString());

            $.ajax({
                url: '{{ url('/api/get-reservations') }}', // Crea questa rotta per restituire i dati necessari
                method: 'GET',
                data: {
                    date: date
                },
                success: function(response) {
                    var container = $('#reservations-list');
                    container.empty();

                    if (response.data.length === 0) {
                        container.append(
                            '<p class="text-sm text-gray-500 dark:text-gray-400 uppercase text-center pt-10">{{ __('Nessuna prenotazione per questa data.') }}</p>'
                        );
                    } else {
                        response.data.forEach(function(item) {
                            container.append(
                                '<div><div class="py-2 border-b border-gray-300 dark:border-gray-700 w-full flex space-x-3"><div class="flex-0 mt-1">' +
                                item.vehicle.logo +
                                '</div><div class="text-gray-700 dark:text-gray-300 flex-1 uppercase"><div class="font-medium text-lg">' +
                                item.driver.first_name + ' ' + item.driver.last_name +
                                '</div><div class="flex justify-between text-sm text-gray-400 dark:text-gray-400"><div class="flex-1">' +
                                item.vehicle.plate +
                                '</div><div class="ml-2  flex-0">' +
                                item.vehicle.brand + ' ' + item.vehicle.model +
                                '</div></div></div></div></div></div>'
                            );
                        });
                    }
                }
            });
        }
    </script>
</x-app-layout>
