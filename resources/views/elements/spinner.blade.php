<style>
    #spinner-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        /* Semi-transparent overlay */
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
        /* Center the spinner */
    }
</style>


<!-- Loading Spinner Overlay -->
<div id="spinner-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.2); z-index: 1000; justify-content: center; align-items: center;">

    <div class="spinner-border mr-3 spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<script>
    function reloadPage() {
        location.reload();
    }
</script>

<script>

    // Show the spinner before the page is refreshed
    window.addEventListener('beforeunload', function() {
        // Display the spinner overlay
        document.getElementById('spinner-overlay').style.display = 'flex';
    });
</script>
