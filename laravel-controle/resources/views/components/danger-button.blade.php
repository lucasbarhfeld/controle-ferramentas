<button {{ $attributes->merge(['type' => 'submit', 'class' => 'app-button app-button-danger']) }}>
    {{ $slot }}
</button>
