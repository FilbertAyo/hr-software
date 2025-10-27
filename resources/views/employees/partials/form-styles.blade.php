{{-- Shared Styles for Employee Forms --}}
<style>
    .progress-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2rem 0;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .step.active .step-circle {
        background-color: #007bff;
        color: white;
    }

    .step.completed .step-circle {
        background-color: #28a745;
        color: white;
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6c757d;
        text-align: center;
    }

    .step.active .step-label {
        color: #007bff;
    }

    .step.completed .step-label {
        color: #28a745;
    }

    .step-line {
        width: 100px;
        height: 2px;
        background-color: #e9ecef;
        margin: 0 1rem;
        margin-top: -20px;
    }

    .step.completed+.step-line {
        background-color: #28a745;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .step-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1rem;
    }

    .step-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-valid {
        border-color: #28a745;
    }

    .invalid-feedback {
        display: block;
    }

    .valid-feedback {
        display: block;
    }

    .card {
        border-radius: 0.5rem;
    }

    .alert {
        border-radius: 0.5rem;
    }
</style>
