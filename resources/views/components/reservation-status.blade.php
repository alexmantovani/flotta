@props(['status'])

@switch($status)
    @case('confirmed')
        <div
            {{ $attributes->merge([
                'class' => 'flex items-center font-medium text-xs rounded-full p-1',
            ]) }}>
            <span class="w-2.5 h-2.5 bg-green-400 rounded-full mr-2"></span>
            CONFERMATA
        </div>
    @break

    @case('pending')
    <div
        {{ $attributes->merge([
            'class' => 'flex items-center font-medium text-xs rounded-full p-1',
        ]) }}>
        <span class="w-2.5 h-2.5 bg-yellow-400 rounded-full mr-2"></span>
        IN ATTESA
    </div>
@break

    @default
        <div
            {{ $attributes->merge([
                'class' => 'flex items-center font-medium text-sm rounded-full p-1',
            ]) }}>
            <span class="w-2.5 h-2.5 bg-gray-400 rounded-full mr-2"></span>
            NON DISPONIBILE
        </div>
@endswitch
