<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Veicoli') }}
            </h2>

            <button>
                <a href="{{ route('vehicle.create') }}">
                    {{ __('Aggiungi veicolo') }}
                </a>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <table
                    class="min-w-full border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="w-12 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Marchio
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Marca
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Modello
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Targa
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Stato
                            </th>

                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($vehicles as $vehicle)
                            <tr onclick="window.location.href='{{ route('vehicle.show', $vehicle->id) }}';"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                <td class="w-12 px-6 py-4 whitespace-nowrap">
                                    {!! $vehicle->logo !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap  text-gray-500 dark:text-gray-400">
                                    {{ $vehicle->brand }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $vehicle->model }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-gray-200">
                                    {{ $vehicle->plate }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-200">
                                    <x-vehicle-availability :availability="$vehicle->availability()" />
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</x-app-layout>
