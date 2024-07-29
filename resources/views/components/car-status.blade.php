@props(['reservation'])

{{-- @if ($reservation->date->isSameDay($date)) --}}
    <div {{ $attributes->merge([
        'class' => 'bg-red-500 text-white',
    ]) }}>
        Busy
    </div>
{{-- @else
    <div {{ $attributes->merge([
        'class' => 'bg-green-500 text-white',
    ]) }}>

    </div>
@endif --}}
