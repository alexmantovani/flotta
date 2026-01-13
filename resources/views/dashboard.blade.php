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
                        <input type="text" id="timeline-search" placeholder="Cerca veicolo o pilota..."
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

    <style>
        /* Timeline styles */
        .timeline-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .dark .timeline-wrapper {
            border-color: #374151;
        }

        .timeline-header-row {
            display: flex;
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .dark .timeline-header-row {
            background: #111827;
            border-bottom-color: #374151;
        }

        .timeline-vehicle-header {
            width: 180px;
            min-width: 180px;
            padding: 8px 12px;
            font-weight: 700;
            font-size: 14px;
            border-right: 2px solid #e5e7eb;
            background: #f9fafb;
            position: sticky;
            left: 0;
            z-index: 11;
        }

        .dark .timeline-vehicle-header {
            background: #111827;
            border-right-color: #374151;
        }

        .timeline-dates-header {
            display: grid;
            flex: 1;
            min-width: 0;
        }

        .timeline-date-cell {
            padding: 6px 4px;
            text-align: center;
            font-size: 11px;
            border-right: 1px solid #e5e7eb;
        }

        .dark .timeline-date-cell {
            border-right-color: #374151;
        }

        .timeline-date-cell.today {
            background-color: #fef3c7;
            font-weight: 700;
        }

        .dark .timeline-date-cell.today {
            background-color: #78350f;
        }

        .timeline-row {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
        }

        .dark .timeline-row {
            border-bottom-color: #374151;
        }

        .timeline-row:hover {
            background-color: #f9fafb;
        }

        .dark .timeline-row:hover {
            background-color: #1f2937;
        }

        .timeline-vehicle-cell {
            width: 180px;
            min-width: 180px;
            padding: 8px 12px;
            border-right: 2px solid #e5e7eb;
            background: white;
            position: sticky;
            left: 0;
            z-index: 5;
        }

        .dark .timeline-vehicle-cell {
            background: #1f2937;
            border-right-color: #374151;
        }

        .timeline-row:hover .timeline-vehicle-cell {
            background-color: #f9fafb;
        }

        .dark .timeline-row:hover .timeline-vehicle-cell {
            background-color: #1f2937;
        }

        .timeline-cells-container {
            display: grid;
            flex: 1;
            position: relative;
            min-width: 0;
        }

        .timeline-day-cell {
            border-right: 1px solid #e5e7eb;
            min-height: 50px;
            position: relative;
            background: white;
        }

        .dark .timeline-day-cell {
            border-right-color: #374151;
            background: #1f2937;
        }

        .timeline-day-cell.today {
            background-color: #fffbeb;
        }

        .dark .timeline-day-cell.today {
            background-color: #1e3a5f;
        }

        .booking-bar {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 26px;
            background: #86efac;
            border-radius: 13px;
            padding: 0 10px;
            color: #065f46;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: move;
            transition: box-shadow 0.2s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            z-index: 2;
            user-select: none;
        }

        .booking-bar:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 3;
        }

        .booking-bar.dragging {
            opacity: 0.7;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            z-index: 100;
        }

        .booking-bar.maintenance {
            background: #fca5a5;
            color: #7f1d1d;
        }

        .booking-bar.pending {
            background: #93c5fd;
            color: #1e3a8a;
        }

        .resize-handle {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 10px;
            cursor: ew-resize;
            z-index: 4;
        }

        .resize-handle-left {
            left: 0;
            border-top-left-radius: 13px;
            border-bottom-left-radius: 13px;
        }

        .resize-handle-right {
            right: 0;
            border-top-right-radius: 13px;
            border-bottom-right-radius: 13px;
        }

        .resize-handle:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .delete-booking-btn {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s;
            opacity: 0;
        }

        .booking-bar:hover .delete-booking-btn {
            opacity: 1;
        }

        .delete-booking-btn:hover {
            background: #fee2e2;
            transform: translateY(-50%) scale(1.1);
        }

        .delete-booking-btn svg {
            width: 12px;
            height: 12px;
            color: #dc2626;
        }

        .timeline-day-cell.drop-target {
            background-color: #dbeafe !important;
        }

        .dark .timeline-day-cell.drop-target {
            background-color: #1e40af !important;
        }

        .timeline-day-cell.conflict {
            background-color: #fee2e2 !important;
        }

        .dark .timeline-day-cell.conflict {
            background-color: #7f1d1d !important;
        }

        .today-marker {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ef4444;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
    </style>

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
                        <div class="font-bold text-sm">${vehicle.plate}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${vehicle.brand} ${vehicle.model}</div>
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
</x-app-layout>
