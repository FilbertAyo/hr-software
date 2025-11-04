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
  animation: slideInRight 0.4s ease-out;
}

.toast.hiding {
  animation: slideOutRight 0.4s ease-in;
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOutRight {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}

</style>

@if (session('success'))

<div class="toast-container">
    <div class="toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true" id="toast-notification">
      <div class="toast-header">
        <strong class="mr-auto">Success!</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body bg-white">{{ session('success') }}</div>
    </div>
  </div>
@elseif (session('error'))
<div class="toast-container">
    <div class="toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="toast-notification">
      <div class="toast-header">
        <strong class="mr-auto">Error!</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body bg-white">{{ session('error') }} </div>
    </div>
  </div>
@endif

<script>
function closeToast() {
  const toast = document.getElementById('toast-notification');
  if (toast) {
    toast.classList.add('hiding');
    setTimeout(() => {
      toast.parentElement.remove();
    }, 400);
  }
}

// Auto-dismiss after 5 seconds
@if (session('success') || session('error'))
document.querySelectorAll('.toast .close').forEach(btn => {
  btn.addEventListener('click', () => {
    closeToast();
  });
});
@endif
</script>
