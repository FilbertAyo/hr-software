<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-sm btn-secondary']) }}>
    {{ $slot }}
</button>
