@props([
    'type' => 'sem_responsavel',
    'class' => 'h-5 w-5',
])

<svg
    {{ $attributes->merge(['class' => $class]) }}
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
>
    @switch($type)
        @case('usuario')
            <circle cx="12" cy="8" r="3.5" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 20a7 7 0 0 1 14 0" />
            @break

        @case('armario_coletivo')
            <rect x="5" y="3" width="14" height="18" rx="2" />
            <path stroke-linecap="round" d="M5 12h14M15.5 8h.01M15.5 16h.01" />
            @break

        @case('centro_custo')
            <circle cx="12" cy="12" r="9" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 8.5h-5a2 2 0 0 0 0 4h3a2 2 0 0 1 0 4h-5M12 6.5v11" />
            @break

        @default
            <circle cx="12" cy="12" r="9" />
            <path stroke-linecap="round" d="M8 12h8" />
    @endswitch
</svg>
