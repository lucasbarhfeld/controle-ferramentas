<button {{ $attributes->merge(['type' => 'submit', 'class' => 'app-button app-button-primary']) }}>
    {{ $slot }}
</button>
