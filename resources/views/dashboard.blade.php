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
    <div class="py-12 flex">
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
                }
                // validRange: {
                //     start: new Date().toISOString().split("T")[0] // Non permettere la selezione di date antecedenti ad oggi
                // }
            });
            calendar.render();

            // Carica le prenotazioni per la data corrente all'avvio
            updateReservationsList(new Date().toISOString().split('T')[0]);
        });

        function updateReservationsList(date) {
            $('#selected-date').text(new Date(date).toLocaleDateString());

            $.ajax({
                url: '{{ url("/api/get-reservations") }}', // Crea questa rotta per restituire i dati necessari
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
'<div><div class="py-2 border-b border-gray-300 dark:border-gray-700 w-full flex space-x-3"><div class="flex-0 mt-1">'
+ item.vehicle.logo +
'</div><div class="text-gray-700 dark:text-gray-300 flex-1 uppercase"><div class="font-medium text-lg">'
+ item.driver.first_name + ' ' + item.driver.last_name +
'</div><div class="flex justify-between text-sm text-gray-400 dark:text-gray-400"><div class="flex-1">'
+ item.vehicle.plate +
'</div><div class="ml-2  flex-0">'
+ item.vehicle.brand + ' ' + item.vehicle.model +
'</div></div></div></div></div></div>'
                            );
                        });
                    }
                }
            });
        }
    </script>
</x-app-layout>
