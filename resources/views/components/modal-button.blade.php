<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-sm btn-primary','data-toggle'=>'modal','data-target'=>'#varyModal','data-whatever'=>'@mdo']) }}>
   <span class="fe fe-plus"></span> {{ $slot }}
</button>


