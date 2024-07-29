<x-app-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight p-2">
                {{ __('Assegna veicolo') }}
            </h2>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div>
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('reservations.store') }}" method="POST"
                        class="space-y-4 flex-grow flex flex-col">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            <div class="col-span-1">
                                <div id="calendar" class="mb-4"></div>
                            </div>
                            <div class="col-span-1 flex flex-col p-5">
                                @csrf
                                <div class="mb-4">
                                    <label for="vehicle_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Veicolo') }}</label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-select mt-1 block w-full">
                                        <option value="0">{{ __('La prima macchina disponibile per il periodo selezionato') }}</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} -
                                                {{ $vehicle->model }} -
                                                {{ $vehicle->brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="driver_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300" required
                                        autofocus>{{ __('Dipendente') }}</label>
                                    <select name="driver_id" id="driver_id" class="form-select mt-1 block w-full">
                                        <option value="">{{ __('Seleziona...') }}</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="notes"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Note') }}</label>
                                    <textarea name="notes" id="notes" class="form-textarea mt-1 block w-full" rows="5">{{ old('notes') }}</textarea>
                                </div>
                                <input type="hidden" name="dates" id="dates" value="{{ old('dates') }}">

                            </div>
                        </div>
                        <div class="mt-auto flex justify-end mr-3">
                            <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-gray-100 rounded-md">
                                {{ __('Conferma') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var selectedDates = document.getElementById('dates').value.split(',').filter(date => date);
            var today = new Date().toISOString().split('T')[0]; // Data di oggi in formato YYYY-MM-DD
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                unselectAuto: false,
                select: function(info) {
                    var newDate = info.startStr;

                    if (selectedDates.includes(newDate)) {
                        selectedDates = selectedDates.filter(date => date !== newDate);
                    } else {
                        selectedDates.push(newDate);
                    }

                    document.getElementById('dates').value = selectedDates.join(',');

                    // Rimuovi tutti gli eventi e ripristina evidenziazioni
                    calendar.getEvents().forEach(event => event.remove());

                    // Aggiungi eventi con sfondo rosso
                    selectedDates.forEach(date => {
                        calendar.addEvent({
                            start: date,
                            display: 'background',
                            backgroundColor: 'rgba(255, 22, 0, 0.8)' // Colore di sfondo rosso
                        });
                    });
                },
                eventClick: function(info) {
                    var date = info.event.startStr;
                    selectedDates = selectedDates.filter(d => d !== date);
                    document.getElementById('dates').value = selectedDates.join(',');
                    info.event.remove();
                },
                events: selectedDates.map(date => ({
                    start: date,
                    display: 'background',
                    backgroundColor: 'rgba(255, 110, 0, 0.3)' // Colore di sfondo rosso
                })),
                dayCellDidMount: function(info) {
                    if (selectedDates.includes(info.dateStr)) {
                        $(info.el).css('background-color',
                            'rgba(255, 10, 10, 0.8)'); // Colore di sfondo rosso
                    } else if (info.dateStr < today) {
                        $(info.el).css('background-color', 'transparent');
                    }
                }
            });
            calendar.render();
        });
    </script>

</x-app-layout>
