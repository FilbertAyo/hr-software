<style>

    .toast-container {
  position: relative;
  width: 100%;
}

.toast {
  position: absolute;
  top: 10px;
  right: 30px;
  z-index: 1050;
  width: auto;
}

</style>

@if (session('success'))

<div class="toast-container">
    <div class="toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="mr-auto">Success!</strong>
        <small>Now</small>

      </div>
      <div class="toast-body bg-white">{{ session('success') }}</div>
    </div>
  </div>
@elseif (session('error'))
<div class="toast-container">
    <div class="toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="mr-auto">Error!</strong>
        <small>Now</small>

      </div>
      <div class="toast-body bg-white">{{ session('error') }} </div>
    </div>
  </div>
@endif


