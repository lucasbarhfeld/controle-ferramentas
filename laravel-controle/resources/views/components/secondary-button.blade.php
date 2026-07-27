<button {{ $attributes->merge(['type' => 'button', 'class' => 'app-button app-button-secondary']) }}>
    {{ $slot }}
</button>
