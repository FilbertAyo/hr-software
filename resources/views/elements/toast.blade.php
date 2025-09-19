<style>

    .toast-container {
  position: relative; /* Ensure this container is the reference for positioning */
  width: 100%; /* Make sure the container spans the width of the screen */
}

.toast {
  position: absolute;
  top: 10px; /* Distance from the top */
  right: 30px; /* Distance from the right edge */
  z-index: 1050; /* Keep it above other elements */
  width: auto; /* Ensure the toast doesn’t stretch unnecessarily */
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
      <div class="toast-body bg-white">{{ session('success') }} </div>
    </div>
  </div>
@endif


