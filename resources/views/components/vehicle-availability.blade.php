@props(['availability'])

@switch($availability)
    @case('available')
        <div
            {{ $attributes->merge([
                'class' => 'flex items-center w-36 font-medium text-xs rounded-full p-1',
            ]) }}>
            <span class="w-2.5 h-2.5 bg-green-400 rounded-full mr-2"></span>
            DISPONIBILE
        </div>
    @break

    @case('maintenance')
        <div
            {{ $attributes->merge([
                'class' => 'flex items-center w-36 font-medium text-xs rounded-full p-1',
            ]) }}>
            <span class="w-2.5 h-2.5 bg-red-400 rounded-full mr-2"></span>
            IN MANUTENZIONE
        </div>
    @break

    @case('reserved')
        <div
            {{ $attributes->merge([
                'class' => 'flex items-center w-36 font-medium text-xs rounded-full p-1',
            ]) }}>
            <span class="w-2.5 h-2.5 bg-blue-400 rounded-full mr-2"></span>
            PRENOTATA
        </div>
    @break

    @default
        <div
            {{ $attributes->merge([
                'class' => 'flex items-center w-36 font-medium text-sm rounded-full p-1',
            ]) }}>
            <span class="w-2.5 h-2.5 bg-gray-400 rounded-full mr-2"></span>
            {{ $availability }}
        </div>
@endswitch
