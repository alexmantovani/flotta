<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vehicles') }}
            </h2>
            <button>
                <a href="{{ route('reservations.create') }}">
                    Add reservation
                </a>
            </button>
            <button>
                <a href="{{ route('vehicles.create') }}">
                    Add Car
                </a>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                @foreach ($vehicles as $vehicle)
                    <div>{{ $vehicle->plate }} - {{ $vehicle->model }}
                        <a href="{{ route('vehicles.show', $vehicle->id) }}">View</a>
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}">Edit</a>
                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                @endforeach


            </div>
        </div>
    </div>

</x-app-layout>
