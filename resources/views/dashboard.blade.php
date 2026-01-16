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

    @if(!auth()->user()->isAdmin())
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4 mt-4">
        <div class="bg-blue-100 dark:bg-blue-900 border border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-200 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Modalità Utente:</strong>
            <span class="block sm:inline">Stai visualizzando solo le tue prenotazioni. Non puoi modificare o eliminare prenotazioni.</span>
        </div>
    </div>
    @endif

    <!-- Card Statistiche -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8 pb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Veicoli Totali -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Veicoli Totali</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalVehicles }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Veicoli Liberi -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Veicoli Liberi</div>
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $vehiclesAvailableToday }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">disponibili oggi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Veicoli Prenotati -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Veicoli Prenotati</div>
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $vehiclesReservedToday }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">in uso oggi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Veicoli in Manutenzione -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">In Manutenzione</div>
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $vehiclesInMaintenanceToday }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">oggi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riga aggiuntiva per Prenotazioni -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Prenotazioni Oggi -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Prenotazioni Oggi</div>
                            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $reservationsToday }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">attive</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prenotazioni in Attesa -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">In Attesa</div>
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $reservationsPending }}</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">da approvare</div>
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin() && $reservationsPending > 0)
                        <div class="ml-4">
                            <a href="{{ route('reservation.validate') }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Vai
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 ">
        <div id="dashboard-container" class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-4">
                <!-- Navigation and Search -->
                <div class="flex items-center justify-between mb-4 gap-4">
                    <div class="flex items-center gap-2">
                        <button id="prev-week"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            ← Precedente
                        </button>
                        <button id="today-btn"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            Oggi
                        </button>
                        <button id="next-week"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Successivo →
                        </button>
                    </div>

                    <div class="relative flex-1 max-w-md">
                        <input type="text" id="timeline-search" placeholder="Cerca veicolo o guidatore..."
                            class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <button id="clear-search"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Timeline Container -->
                <div id="timeline-container" class="overflow-x-auto">
                    <!-- Il timeline verrà caricato qui via jQuery -->
                </div>
            </div>

        </div>

    </div>

    <script>
        let currentStartDate = null;
        let currentEndDate = null;
        let currentVehiclesData = null;
        let currentDatesData = null;
        let isUpdating = false; // Flag per prevenire operazioni simultanee

        $(document).ready(function() {
            // Inizializza timeline partendo dalla domenica della settimana corrente
            const today = new Date();

            // Trova la domenica di questa settimana (0 = domenica)
            const dayOfWeek = today.getDay();
            const daysToSunday = dayOfWeek; // Se oggi è domenica = 0, se lunedì = 1, ecc.

            currentStartDate = new Date(today);
            currentStartDate.setDate(today.getDate() - daysToSunday);

            // Mostra 14 giorni (2 settimane)
            currentEndDate = new Date(currentStartDate);
            currentEndDate.setDate(currentStartDate.getDate() + 13); // 13 giorni dopo = 14 giorni totali

            loadTimeline();

            // Navigazione timeline
            $('#prev-week').on('click', function() {
                // Vai indietro di una settimana (7 giorni)
                currentStartDate.setDate(currentStartDate.getDate() - 7);
                currentEndDate.setDate(currentEndDate.getDate() - 7);
                $('#timeline-search').val('');
                $('#clear-search').addClass('hidden');
                loadTimeline();
            });

            $('#next-week').on('click', function() {
                // Vai avanti di una settimana (7 giorni)
                currentStartDate.setDate(currentStartDate.getDate() + 7);
                currentEndDate.setDate(currentEndDate.getDate() + 7);
                $('#timeline-search').val('');
                $('#clear-search').addClass('hidden');
                loadTimeline();
            });

            $('#today-btn').on('click', function() {
                // Torna alla domenica della settimana corrente
                const today = new Date();
                const dayOfWeek = today.getDay();
                const daysToSunday = dayOfWeek;

                currentStartDate = new Date(today);
                currentStartDate.setDate(today.getDate() - daysToSunday);

                currentEndDate = new Date(currentStartDate);
                currentEndDate.setDate(currentStartDate.getDate() + 13);

                $('#timeline-search').val('');
                $('#clear-search').addClass('hidden');
                loadTimeline();
            });

            // Ricerca nel timeline
            $('#timeline-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterTimeline(searchTerm);

                // Mostra/nascondi bottone clear
                if (searchTerm) {
                    $('#clear-search').removeClass('hidden');
                } else {
                    $('#clear-search').addClass('hidden');
                }
            });

            // Clear search
            $('#clear-search').on('click', function() {
                $('#timeline-search').val('');
                $('#clear-search').addClass('hidden');
                filterTimeline('');
            });
        });

        function loadTimeline() {
            const startDateStr = formatDate(currentStartDate);
            const endDateStr = formatDate(currentEndDate);

            $.ajax({
                url: '{{ url('/api/get-timeline-data') }}',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    start_date: startDateStr,
                    end_date: endDateStr
                },
                success: function(response) {
                    // Salva le date globalmente per il drag & drop
                    window.timelineDates = response.dates;
                    currentVehiclesData = response.vehicles;
                    currentDatesData = response.dates;
                    renderTimeline(response.vehicles, response.dates);
                },
                error: function(xhr) {
                    console.error('Errore nel caricamento del timeline:', xhr);
                }
            });
        }

        function renderTimeline(vehicles, dates) {
            const container = $('#timeline-container');
            container.empty();

            if (vehicles.length === 0) {
                container.append(
                    '<p class="text-center text-gray-500 dark:text-gray-400 py-8">Nessun veicolo disponibile</p>');
                return;
            }

            const today = formatDate(new Date());
            const wrapper = $('<div class="timeline-wrapper"></div>');

            // Header row
            const headerRow = $('<div class="timeline-header-row"></div>');
            headerRow.append('<div class="timeline-vehicle-header text-gray-700 dark:text-gray-300">VEICOLI</div>');

            const datesHeader = $('<div class="timeline-dates-header"></div>');
            // Imposta il grid con colonne uguali
            const gridColumns = `repeat(${dates.length}, 1fr)`;
            datesHeader.css('grid-template-columns', gridColumns);

            dates.forEach(dateStr => {
                const date = new Date(dateStr + 'T00:00:00');
                const dayName = date.toLocaleDateString('it-IT', {
                    weekday: 'short'
                });
                const dayNum = date.getDate();
                const monthName = date.toLocaleDateString('it-IT', {
                    month: 'short'
                });
                const isToday = dateStr === today;

                const dateCell = $(`
                    <div class="timeline-date-cell ${isToday ? 'today' : ''} text-gray-600 dark:text-gray-400">
                        <div class="font-semibold">${dayName}</div>
                        <div class="text-xs">${dayNum} ${monthName}</div>
                    </div>
                `);
                datesHeader.append(dateCell);
            });
            headerRow.append(datesHeader);
            wrapper.append(headerRow);

            // Veicoli rows
            vehicles.forEach(vehicle => {
                const row = $('<div class="timeline-row"></div>');

                // Vehicle label
                const vehicleCell = $(`
                    <div class="timeline-vehicle-cell text-gray-700 dark:text-gray-300">
                        <a href="/vehicle/${vehicle.id}">
                        <div class="font-bold text-sm">${vehicle.plate}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${vehicle.brand} ${vehicle.model}</div>
                        </a>
                    </div>
                `);
                row.append(vehicleCell);

                // Cells container
                const cellsContainer = $('<div class="timeline-cells-container"></div>');
                // Imposta il grid con colonne uguali
                cellsContainer.css('grid-template-columns', gridColumns);

                // Add day cells
                dates.forEach(dateStr => {
                    const isToday = dateStr === today;
                    const dayCell = $(`<div class="timeline-day-cell ${isToday ? 'today' : ''}"></div>`);

                    // Add today marker
                    if (isToday) {
                        dayCell.append('<div class="today-marker"></div>');
                    }

                    cellsContainer.append(dayCell);
                });

                // Add booking bars
                vehicle.bookings.forEach(booking => {
                    const startIdx = dates.indexOf(booking.start_date);
                    const endIdx = dates.indexOf(booking.end_date);

                    if (startIdx !== -1 && endIdx !== -1) {
                        const duration = endIdx - startIdx + 1;
                        const totalDays = dates.length;

                        // Calculate position and width with 10px margins
                        // Usa una proporzione esatta: ogni cella occupa 1/totalDays della larghezza
                        const cellFraction = 1 / totalDays;
                        const leftFraction = startIdx * cellFraction;
                        const widthFraction = duration * cellFraction;

                        // Converti in percentuale
                        const leftPercent = leftFraction * 100;
                        const widthPercent = widthFraction * 100;

                        const statusClass = booking.status === 'maintenance' ? 'maintenance' :
                            booking.status === 'pending' ? 'pending' : '';

                        // Formatta le date EFFETTIVE per il tooltip (non troncate)
                        const actualStartDate = booking.actual_start_date || booking.start_date;
                        const actualEndDate = booking.actual_end_date || booking.end_date;

                        const startDateObj = new Date(actualStartDate + 'T00:00:00');
                        const endDateObj = new Date(actualEndDate + 'T00:00:00');
                        const startDateFormatted = startDateObj.toLocaleDateString('it-IT', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                        const endDateFormatted = endDateObj.toLocaleDateString('it-IT', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });

                        const bar = $(`
                            <div class="booking-bar ${statusClass}"
                                 style="left: calc(${leftPercent}% + 10px); width: calc(${widthPercent}% - 20px);"
                                 data-vehicle-id="${vehicle.id}"
                                 data-start-date="${booking.start_date}"
                                 data-end-date="${booking.end_date}"
                                 data-actual-start-date="${actualStartDate}"
                                 data-actual-end-date="${actualEndDate}"
                                 data-reservation-ids='${JSON.stringify(booking.reservation_ids)}'
                                 data-driver-name="${booking.driver_name}"
                                 data-start-idx="${startIdx}"
                                 data-end-idx="${endIdx}"
                                 data-total-days="${totalDays}"
                                 title="${booking.driver_name} • ${startDateFormatted} → ${endDateFormatted}${booking.note ? '\n' + booking.note : ''}">
                                <div class="resize-handle resize-handle-left"></div>
                                <span class="text-sm font-semibold">${booking.driver_name}</span>
                                <div class="delete-booking-btn" title="Elimina prenotazione">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <div class="resize-handle resize-handle-right"></div>
                            </div>
                        `);

                        cellsContainer.append(bar);
                    }
                });

                row.append(cellsContainer);
                wrapper.append(row);
            });

            container.append(wrapper);

            // Inizializza drag & drop e resize
            initializeDragAndResize();
        }

        function initializeDragAndResize() {
            let isDragging = false;
            let isResizing = false;
            let resizeDirection = null;
            let currentBar = null;
            let startX = 0;
            let originalLeft = 0;
            let originalWidth = 0;
            let cellWidth = 0;

            // Gestione eliminazione booking
            $(document).on('click', '.delete-booking-btn', function(e) {
                e.stopPropagation();
                e.preventDefault();

                const bar = $(this).closest('.booking-bar');
                const driverName = bar.attr('data-driver-name');
                const reservationIds = JSON.parse(bar.attr('data-reservation-ids'));
                const vehicleId = bar.attr('data-vehicle-id');

                if (confirm(`Sei sicuro di voler eliminare la prenotazione di ${driverName}?`)) {
                    deleteBooking(reservationIds, vehicleId);
                }
            });

            // Drag & Drop
            $(document).on('mousedown', '.booking-bar', function(e) {
                // Previeni drag/resize se c'è un aggiornamento in corso
                if (isUpdating) {
                    return;
                }

                // Verifica se è il pulsante elimina
                if ($(e.target).closest('.delete-booking-btn').length > 0) {
                    return; // Non fare nulla, lascia gestire al click handler
                }

                // Verifica se è un handle di resize
                if ($(e.target).hasClass('resize-handle')) {
                    isResizing = true;
                    resizeDirection = $(e.target).hasClass('resize-handle-left') ? 'left' : 'right';
                } else {
                    isDragging = true;
                }

                currentBar = $(this);
                startX = e.pageX;

                const barPosition = currentBar.position();
                originalLeft = parseFloat(currentBar.css('left'));
                originalWidth = currentBar.width();

                // Calcola la larghezza di una cella
                const container = currentBar.parent();
                const totalCells = container.find('.timeline-day-cell').length;
                cellWidth = container.width() / totalCells;

                currentBar.addClass('dragging');
                e.preventDefault();
            });

            $(document).on('mousemove', function(e) {
                if (!isDragging && !isResizing) return;

                const diffX = e.pageX - startX;

                if (isDragging) {
                    // Drag: sposta la barra
                    const newLeft = originalLeft + diffX;
                    currentBar.css('left', newLeft + 'px');
                } else if (isResizing) {
                    // Resize
                    if (resizeDirection === 'left') {
                        // Ridimensiona da sinistra
                        const newLeft = originalLeft + diffX;
                        const newWidth = originalWidth - diffX;
                        if (newWidth >= cellWidth * 0.8) { // Minimo 1 giorno
                            currentBar.css({
                                'left': newLeft + 'px',
                                'width': newWidth + 'px'
                            });
                        }
                    } else if (resizeDirection === 'right') {
                        // Ridimensiona da destra
                        const newWidth = originalWidth + diffX;
                        if (newWidth >= cellWidth * 0.8) { // Minimo 1 giorno
                            currentBar.css('width', newWidth + 'px');
                        }
                    }
                }
            });

            $(document).on('mouseup', function(e) {
                if (!isDragging && !isResizing) return;

                if (currentBar) {
                    currentBar.removeClass('dragging');

                    const diffX = e.pageX - startX;
                    const threshold = 5; // Pixel minimi di movimento per considerarlo un drag

                    // Se non c'è stato movimento significativo, non fare nulla
                    if (Math.abs(diffX) < threshold) {
                        isDragging = false;
                        isResizing = false;
                        resizeDirection = null;
                        currentBar = null;
                        return;
                    }

                    // Calcola le nuove date considerando i margini di 10px
                    const container = currentBar.parent();
                    const containerOffset = container.offset().left;
                    const barLeft = currentBar.offset().left - containerOffset;
                    const barRight = barLeft + currentBar.outerWidth();

                    const totalCells = container.find('.timeline-day-cell').length;
                    const containerWidth = container.width();
                    cellWidth = containerWidth / totalCells;

                    // Calcola gli indici arrotondando al più vicino
                    let startIdx = Math.round((barLeft - 10) / cellWidth);
                    let endIdx = Math.round((barRight + 10) / cellWidth) - 1;

                    // Limita agli indici validi
                    startIdx = Math.max(0, Math.min(startIdx, totalCells - 1));
                    endIdx = Math.max(startIdx, Math.min(endIdx, totalCells - 1));

                    // Recupera le date effettive (potrebbero essere fuori dalla griglia)
                    const actualStartDate = currentBar.attr('data-actual-start-date');
                    const actualEndDate = currentBar.attr('data-actual-end-date');
                    const oldStartDate = currentBar.attr('data-start-date');
                    const oldEndDate = currentBar.attr('data-end-date');

                    let newStartDate, newEndDate;

                    if (window.timelineDates && window.timelineDates.length > 0) {
                        if (isResizing) {
                            // Durante il resize
                            if (resizeDirection === 'left') {
                                // Resize da sinistra: cambia solo start_date, mantieni actual_end_date
                                newStartDate = window.timelineDates[startIdx];
                                newEndDate = actualEndDate;
                            } else {
                                // Resize da destra: mantieni actual_start_date, cambia solo end_date
                                newStartDate = actualStartDate;
                                newEndDate = window.timelineDates[endIdx];
                            }
                        } else if (isDragging) {
                            // Durante il drag: sposta entrambe le date dello stesso offset
                            const newVisibleStartDate = window.timelineDates[startIdx];
                            const newVisibleEndDate = window.timelineDates[endIdx];

                            // Calcola lo spostamento in giorni
                            const oldStartIdx = currentBar.attr('data-start-idx');
                            const daysDiff = startIdx - parseInt(oldStartIdx);

                            if (daysDiff !== 0) {
                                // Applica lo spostamento alle date effettive
                                const actualStart = new Date(actualStartDate + 'T00:00:00');
                                const actualEnd = new Date(actualEndDate + 'T00:00:00');

                                actualStart.setDate(actualStart.getDate() + daysDiff);
                                actualEnd.setDate(actualEnd.getDate() + daysDiff);

                                newStartDate = formatDate(actualStart);
                                newEndDate = formatDate(actualEnd);
                            } else {
                                newStartDate = actualStartDate;
                                newEndDate = actualEndDate;
                            }
                        }

                        // Salva solo se le date sono cambiate
                        if (newStartDate !== actualStartDate || newEndDate !== actualEndDate) {
                            saveBookingUpdate(currentBar, newStartDate, newEndDate);
                        } else {
                            // Ripristina la posizione originale se non c'è cambio di date
                            loadTimeline();
                        }
                    }
                }

                isDragging = false;
                isResizing = false;
                resizeDirection = null;
                currentBar = null;
            });
        }

        function saveBookingUpdate(bar, newStartDate, newEndDate) {
            if (isUpdating) return; // Previeni chiamate multiple
            isUpdating = true;

            const reservationIds = JSON.parse(bar.attr('data-reservation-ids'));
            const vehicleId = bar.attr('data-vehicle-id');
            const driverName = bar.attr('data-driver-name');

            $.ajax({
                url: '{{ url('/api/update-booking-dates') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    reservation_ids: reservationIds,
                    vehicle_id: vehicleId,
                    new_start_date: newStartDate,
                    new_end_date: newEndDate
                },
                success: function(response) {
                    // Reset ricerca e ricarica il timeline
                    $('#timeline-search').val('');
                    $('#clear-search').addClass('hidden');
                    loadTimeline();

                    // Mostra notifica di successo
                    showNotification('Prenotazione aggiornata con successo', 'success');
                    isUpdating = false;
                },
                error: function(xhr) {
                    // Reset ricerca e ricarica il timeline per ripristinare la posizione originale
                    $('#timeline-search').val('');
                    $('#clear-search').addClass('hidden');
                    loadTimeline();

                    const error = xhr.responseJSON?.error || 'Errore durante l\'aggiornamento';
                    showNotification(error, 'error');
                    isUpdating = false;
                }
            });
        }

        function deleteBooking(reservationIds, vehicleId) {
            if (isUpdating) return; // Previeni chiamate multiple
            isUpdating = true;

            $.ajax({
                url: '{{ url('/api/delete-booking') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    reservation_ids: reservationIds,
                    vehicle_id: vehicleId
                },
                success: function(response) {
                    // Reset ricerca e ricarica il timeline
                    $('#timeline-search').val('');
                    $('#clear-search').addClass('hidden');
                    loadTimeline();

                    // Mostra notifica di successo
                    showNotification('Prenotazione eliminata con successo', 'success');
                    isUpdating = false;
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.error || 'Errore durante l\'eliminazione';
                    showNotification(error, 'error');
                    isUpdating = false;
                }
            });
        }

        function showNotification(message, type) {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const notification = $(`
                <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50">
                    ${message}
                </div>
            `);

            $('body').append(notification);

            setTimeout(() => {
                notification.fadeOut(300, () => notification.remove());
            }, 3000);
        }

        function filterTimeline(searchTerm) {
            if (!currentVehiclesData || !currentDatesData) return;

            if (!searchTerm) {
                // Mostra tutti i veicoli
                renderTimeline(currentVehiclesData, currentDatesData);
                return;
            }

            // Filtra i veicoli in base al termine di ricerca
            const filteredVehicles = currentVehiclesData.filter(vehicle => {
                // Cerca nella targa, brand, model
                const vehicleMatch =
                    vehicle.plate.toLowerCase().includes(searchTerm) ||
                    vehicle.brand.toLowerCase().includes(searchTerm) ||
                    vehicle.model.toLowerCase().includes(searchTerm);

                // Cerca nei nomi dei piloti delle prenotazioni
                const driverMatch = vehicle.bookings.some(booking =>
                    booking.driver_name.toLowerCase().includes(searchTerm)
                );

                return vehicleMatch || driverMatch;
            });

            // Renderizza solo i veicoli filtrati
            renderTimeline(filteredVehicles, currentDatesData);

            // Mostra messaggio se nessun risultato
            if (filteredVehicles.length === 0) {
                $('#timeline-container').append(`
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        Nessun risultato trovato per "${searchTerm}"
                    </div>
                `);
            }
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    </script>

    @if(!auth()->user()->isAdmin())
    <style>
        .delete-booking-btn,
        .resize-handle {
            display: none !important;
        }
        .booking-bar {
            cursor: default !important;
        }
    </style>
    @endif
</x-app-layout>
