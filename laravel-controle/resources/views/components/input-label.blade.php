@props(['value'])

<label {{ $attributes->merge(['class' => 'app-label block text-sm font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
