<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Conducenti') }}
            </h2>

            <div class="flex gap-3">
                <button onclick="document.getElementById('import-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    Importa CSV
                </button>
                <a href="{{ route('driver.create') }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    {{ __('Aggiungi conducente') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('driver.index') }}">
                    <div class="flex gap-3 items-end">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cerca per nome, cognome, matricola o email..."
                                    class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                @if(request('search'))
                                    <a href="{{ route('driver.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div class="w-48">
                            <select
                                name="availability"
                                id="availability"
                                class="w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tutti gli stati</option>
                                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Disponibili</option>
                                <option value="assigned" {{ request('availability') == 'assigned' ? 'selected' : '' }}>Assegnati</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <button
                            type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-500 whitespace-nowrap">
                            Cerca
                        </button>
                        <a
                            href="{{ route('driver.index') }}"
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition whitespace-nowrap">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Results Counter -->
                @if(request()->hasAny(['search', 'availability']))
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Trovati <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $drivers->count() }}</span>
                            {{ $drivers->count() === 1 ? 'conducente' : 'conducenti' }}
                        </p>
                    </div>
                @endif

                <table
                    class="min-w-full border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nome
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cognome
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Matricola
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Stato
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($drivers as $driver)
                            <tr onclick="window.location.href='{{ route('driver.show', $driver->id) }}';"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-gray-200">
                                    {{ $driver->first_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-gray-200">
                                    {{ $driver->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $driver->uuid ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $driver->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-200">
                                    <x-driver-status :availability="$driver->availability" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nessun conducente trovato</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Prova a modificare i filtri di ricerca o
                                            <a href="{{ route('driver.index') }}" class="text-blue-500 hover:text-blue-600">resetta la ricerca</a>.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Import CSV Modal -->
    <div id="import-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Importa Conducenti da CSV
                </h3>
                <button onclick="document.getElementById('import-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
                <form action="{{ route('driver.import') }}" method="POST" enctype="multipart/form-data" id="import-form">
                    @csrf

                    <!-- Instructions -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-100">Formato del file CSV</h4>
                            <a href="#" onclick="event.preventDefault(); downloadTemplate();"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline">
                                Scarica template
                            </a>
                        </div>
                        <p class="text-sm text-blue-800 dark:text-blue-200 mb-2">
                            Il file deve contenere le seguenti colonne (con intestazione):
                        </p>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 list-disc list-inside space-y-1">
                            <li><strong>first_name</strong> - Nome del conducente (obbligatorio)</li>
                            <li><strong>last_name</strong> - Cognome del conducente (obbligatorio)</li>
                            <li><strong>uuid</strong> - Matricola (opzionale)</li>
                            <li><strong>email</strong> - Email (opzionale, usata per rilevare duplicati)</li>
                            <li><strong>phone_number</strong> - Numero di telefono (opzionale)</li>
                        </ul>
                        <p class="text-sm text-blue-800 dark:text-blue-200 mt-2">
                            <strong>Esempio:</strong>
                        </p>
                        <code class="block mt-1 p-2 bg-white dark:bg-gray-900 rounded text-xs">
                            first_name,last_name,uuid,email,phone_number<br>
                            Mario,Rossi,MAT001,mario.rossi@example.com,+39 123 456 789<br>
                            Luigi,Bianchi,MAT002,luigi.bianchi@example.com,+39 987 654 321
                        </code>
                    </div>

                    <!-- File Input -->
                    <div class="mb-4">
                        <label for="csv_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Seleziona file CSV
                        </label>
                        <input
                            type="file"
                            name="csv_file"
                            id="csv_file"
                            accept=".csv"
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Skip Duplicates Option -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="skip_duplicates" value="1" checked
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Salta email duplicate (se disabilitato, l'import fallirà in caso di email duplicate)
                            </span>
                        </label>
                    </div>

                    <!-- Error Display -->
                    <div id="import-errors" class="hidden mb-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <h4 class="font-semibold text-red-900 dark:text-red-100 mb-2">Errori durante l'importazione</h4>
                        <ul id="error-list" class="text-sm text-red-800 dark:text-red-200 list-disc list-inside"></ul>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button
                            type="button"
                            onclick="document.getElementById('import-modal').classList.add('hidden')"
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Annulla
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            Importa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to download CSV template
        function downloadTemplate() {
            const csvContent = 'first_name,last_name,uuid,email,phone_number\nMario,Rossi,MAT001,mario.rossi@example.com,+39 123 456 789\nLuigi,Bianchi,MAT002,luigi.bianchi@example.com,+39 987 654 321';
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'template_conducenti.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Handle import form submission with AJAX for better UX
        document.getElementById('import-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const errorDiv = document.getElementById('import-errors');
            const errorList = document.getElementById('error-list');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Importazione in corso...';

            // Hide previous errors
            errorDiv.classList.add('hidden');
            errorList.innerHTML = '';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.textContent = data.message || `Importati ${data.imported} conducenti con successo!`;
                    document.body.appendChild(notification);

                    // Reload page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else if (data.errors) {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Importa';

                    // Show errors
                    errorDiv.classList.remove('hidden');

                    // Show summary if partial import
                    if (data.imported > 0) {
                        const summaryLi = document.createElement('li');
                        summaryLi.innerHTML = `<strong>Importati ${data.imported} conducenti con successo.</strong>`;
                        summaryLi.className = 'text-green-600 dark:text-green-400';
                        errorList.appendChild(summaryLi);
                    }

                    data.errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        errorList.appendChild(li);
                    });
                }
            })
            .catch(error => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Importa';

                errorDiv.classList.remove('hidden');
                const li = document.createElement('li');
                li.textContent = 'Errore durante l\'importazione. Riprova.';
                errorList.appendChild(li);
            });
        });
    </script>

</x-app-layout>
