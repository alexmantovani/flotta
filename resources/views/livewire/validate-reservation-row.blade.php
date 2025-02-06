<div>
    @if ($reservation->status == 'pending')

        <tr class="items-center">
            <td class="px-6 py-4 whitespace-no-wrap">
                <div class="capitalize">
                    {{ $reservation->date->locale('it')->isoFormat('DD MMMM Y') }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-no-wrap">
                <div class="uppercase">
                    {{ $reservation->driver->name }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-no-wrap ">
                <div>
                    <select name="vehicle_id" id="vehicle_id" class="form-select mt-1 block w-full">

                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}"
                                {{ $vehicle->id == $reservation->vehicle_id ? 'selected' : '' }}>
                                {{ $vehicle->plate }} -
                                {{ $vehicle->model }} -
                                {{ $vehicle->brand }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td class="px-6 whitespace-no-wrap py-4 text-right">
                <x-danger-button wire:click="rejectReservation()">
                    Rifiuta
                </x-danger-button>
                <x-button wire:click="approveReservation()">
                    Approva
                </x-button>
            </td>
        </tr>
    @endif

</div>
