<x-app-layout>
    <style>
        /* Stili personalizzati per il calendario */
        #calendar {
            background: white;
            padding: 15px;
            border-radius: 8px;
        }

        .fc-daygrid-day {
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .fc-daygrid-day-number {
            font-size: 0.95rem;
            font-weight: 500;
            padding: 8px;
        }

        .fc-daygrid-day-frame {
            min-height: 60px;
        }

        .fc-day {
            border: 1px solid #e5e7eb !important;
            transition: all 0.2s ease;
        }

        .fc-day-today {
            background-color: #fef3c7 !important;
            border: 2px solid #f59e0b !important;
        }

        .fc-daygrid-day:not(.fc-day-past):hover {
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
            z-index: 10;
        }

        /* Stile dei pulsanti del calendario */
        .fc-button {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            text-transform: capitalize !important;
            transition: all 0.2s;
        }

        .fc-button:hover {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            transform: translateY(-1px);
        }

        .fc-button-active {
            background-color: #1e40af !important;
            border-color: #1e40af !important;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            color: #1f2937;
        }

        .fc-col-header-cell-cushion {
            color: #374151;
            font-weight: 600;
            padding: 8px;
        }
    </style>
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
                    <form action="{{ route('reservation.store') }}" method="POST"
                        class="space-y-4 flex-grow flex flex-col">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            <div class="col-span-1">
                                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                        Seleziona i giorni
                                    </h3>
                                    <p class="text-sm text-blue-600 dark:text-blue-300 mb-2">
                                        Clicca sui giorni per selezionarli o deselezionarli
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm">
                                            <span class="font-medium text-blue-800 dark:text-blue-200">Giorni selezionati:</span>
                                            <span id="selected-count" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded-full font-bold">0</span>
                                        </div>
                                        <button type="button" id="clear-selection"
                                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors text-sm">
                                            Cancella tutto
                                        </button>
                                    </div>
                                </div>
                                <div id="calendar" class="mb-4 shadow-lg rounded-lg overflow-hidden"></div>
                                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Legenda:</h4>
                                    <div class="flex flex-wrap gap-3 text-xs">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                            <span class="text-gray-700 dark:text-gray-300">Selezionato</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
                                            <span class="text-gray-700 dark:text-gray-300">Hover (clicca per selezionare)</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-gray-300 rounded mr-2 opacity-50"></div>
                                            <span class="text-gray-700 dark:text-gray-300">Passato (non selezionabile)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-1 flex flex-col p-5">
                                @csrf

                                @if(!auth()->user()->isAdmin())
                                    <!-- User normale: info sul comportamento automatico -->
                                    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-300 dark:border-blue-700 rounded-lg">
                                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                            Prenotazione automatica
                                        </h4>
                                        <p class="text-xs text-blue-600 dark:text-blue-300">
                                            Il sistema assegnerà automaticamente:<br>
                                            • <strong>Conducente:</strong> Tu stesso<br>
                                            • <strong>Veicolo:</strong> Primo veicolo disponibile per il periodo selezionato
                                        </p>
                                    </div>
                                    <input type="hidden" name="vehicle_id" value="0">
                                @else
                                    <!-- Admin: selezione completa -->
                                    <div class="mb-4">
                                        <label for="vehicle_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Veicolo') }}</label>
                                        <select name="vehicle_id" id="vehicle_id" class="form-select mt-1 block w-full">
                                            @if ($vehicles->count() > 1)
                                                <option value="0">
                                                    {{ __('La prima macchina disponibile per il periodo selezionato') }}
                                                </option>
                                                @foreach ($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} -
                                                        {{ $vehicle->model }} -
                                                        {{ $vehicle->brand }}</option>
                                                @endforeach
                                            @else
                                                <option value="{{ $vehicles[0]->id }}">{{ $vehicles[0]->model }} -
                                                    {{ $vehicles[0]->brand }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                @if ($showDriverSelect)
                                    <div class="mb-4">
                                        <label for="driver_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300" required
                                            autofocus>{{ __('Dipendente') }}</label>
                                        <select name="driver_id" id="driver_id" class="form-select mt-1 block w-full">
                                            <option value="">{{ __('Cerca dipendente...') }}</option>
                                        </select>
                                    </div>
                                @endif
                                <div class="mb-4">
                                    <label for="note"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Note') }}</label>
                                    <textarea name="note" id="note" class="form-textarea mt-1 block w-full" rows="5">{{ old('note') }}</textarea>
                                </div>
                                <input type="hidden" name="dates" id="dates" value="{{ old('dates') }}">
                                <input type="hidden" name="status" id="status" value="{{ $status }}">

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
            // Inizializza Select2 per la ricerca dipendenti
            @if ($showDriverSelect)
            $('#driver_id').select2({
                ajax: {
                    url: '/api/search-drivers',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                placeholder: 'Cerca dipendente...',
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return 'Inserisci almeno 2 caratteri';
                    },
                    searching: function() {
                        return 'Ricerca in corso...';
                    },
                    noResults: function() {
                        return 'Nessun dipendente trovato';
                    }
                }
            });
            @endif

            // Gestione calendario
            var selectedDates = document.getElementById('dates').value.split(',').filter(date => date);
            var today = new Date().toISOString().split('T')[0];
            var calendarEl = document.getElementById('calendar');

            // Funzione per aggiornare il contatore
            function updateCounter() {
                document.getElementById('selected-count').textContent = selectedDates.length;
            }

            // Funzione per aggiornare la visualizzazione delle date
            function updateCalendarView() {
                calendar.getEvents().forEach(event => event.remove());
                selectedDates.forEach(date => {
                    calendar.addEvent({
                        start: date,
                        display: 'background',
                        backgroundColor: '#10b981', // Verde
                        borderColor: '#059669'
                    });
                });
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                unselectAuto: false,
                selectAllow: function(selectInfo) {
                    // Permetti solo date future o oggi
                    return selectInfo.startStr >= today;
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                locale: 'it',
                buttonText: {
                    today: 'Oggi'
                },
                select: function(info) {
                    var newDate = info.startStr;

                    // Non permettere selezione di date passate
                    if (newDate < today) {
                        calendar.unselect();
                        return;
                    }

                    if (selectedDates.includes(newDate)) {
                        selectedDates = selectedDates.filter(date => date !== newDate);
                    } else {
                        selectedDates.push(newDate);
                        selectedDates.sort();
                    }

                    document.getElementById('dates').value = selectedDates.join(',');
                    updateCalendarView();
                    updateCounter();
                    calendar.unselect();
                },
                eventClick: function(info) {
                    var date = info.event.startStr;
                    selectedDates = selectedDates.filter(d => d !== date);
                    document.getElementById('dates').value = selectedDates.join(',');
                    info.event.remove();
                    updateCounter();
                },
                dayCellDidMount: function(info) {
                    var cell = $(info.el);

                    if (info.dateStr < today) {
                        // Date passate - grigie e non cliccabili
                        cell.css({
                            'background-color': '#f3f4f6',
                            'color': '#9ca3af',
                            'cursor': 'not-allowed',
                            'opacity': '0.5'
                        });
                    } else if (selectedDates.includes(info.dateStr)) {
                        // Date selezionate - verde brillante
                        cell.css({
                            'background-color': '#10b981',
                            'color': 'white',
                            'font-weight': 'bold',
                            'cursor': 'pointer'
                        });
                    } else {
                        // Date future selezionabili - hover effetto
                        cell.css({
                            'cursor': 'pointer',
                            'transition': 'all 0.2s'
                        });

                        cell.hover(
                            function() {
                                $(this).css({
                                    'background-color': '#d1fae5',
                                    'transform': 'scale(1.05)'
                                });
                            },
                            function() {
                                $(this).css({
                                    'background-color': '',
                                    'transform': 'scale(1)'
                                });
                            }
                        );
                    }
                }
            });

            calendar.render();
            updateCounter();

            // Pulsante cancella tutto
            $('#clear-selection').on('click', function() {
                if (selectedDates.length > 0 && confirm('Vuoi davvero cancellare tutti i giorni selezionati?')) {
                    selectedDates = [];
                    document.getElementById('dates').value = '';
                    updateCalendarView();
                    updateCounter();
                    calendar.refetchEvents();
                    calendar.render();
                }
            });

            // Validazione del form
            $('form').on('submit', function(e) {
                if (selectedDates.length === 0) {
                    e.preventDefault();
                    alert('Devi selezionare almeno un giorno nel calendario prima di confermare.');
                    return false;
                }
                return true;
            });
        });
    </script>

</x-app-layout>
