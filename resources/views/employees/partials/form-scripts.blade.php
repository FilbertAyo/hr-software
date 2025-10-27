{{-- Shared JavaScript for Employee Forms --}}
<script>
    let currentStep = {{ $currentStep ?? 1 }};
    const totalSteps = 2;

    // Auto-save form data to session periodically
    let autoSaveTimeout;

    function autoSaveFormData() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            saveFormDataToSession();
        }, 1000); // Save after 1 second of inactivity
    }

    // Save form data to server session via AJAX
    function saveFormDataToSession() {
        const form = document.getElementById('employeeForm');
        const formData = new FormData(form);
        formData.append('step', currentStep);

        fetch('{{ route('employee.session.save') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Form data saved to session');
            })
            .catch(error => {
                console.error('Error saving form data:', error);
            });
    }

    // Navigate to a specific step
    function navigateToStep(targetStep) {
        if (targetStep < 1 || targetStep > totalSteps) return;

        const form = document.getElementById('employeeForm');
        const formData = new FormData(form);
        formData.append('step', targetStep);

        fetch('{{ route('employee.session.navigate') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Hide all steps
                for (let i = 1; i <= totalSteps; i++) {
                    document.getElementById(`step${i}`).classList.remove('active');
                }

                // Show target step
                currentStep = targetStep;
                document.getElementById(`step${currentStep}`).classList.add('active');

                updateProgressIndicator();

                // Scroll to top
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            })
            .catch(error => {
                console.error('Error navigating:', error);
            });
    }

    function nextStep() {
        if (validateCurrentStep()) {
            saveFormDataToSession();

            if (currentStep < totalSteps) {
                // Mark current step as completed
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');

                navigateToStep(currentStep + 1);
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            saveFormDataToSession();
            navigateToStep(currentStep - 1);
        }
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });

        // Special validation for step 1
        if (currentStep === 1) {
            const email = document.getElementById('email');
            const mobile = document.getElementById('mobile_no');

            if (email && email.value && !isValidEmail(email.value)) {
                email.classList.add('is-invalid');
                isValid = false;
            }

            if (mobile && mobile.value && !isValidMobile(mobile.value)) {
                mobile.classList.add('is-invalid');
                isValid = false;
            }
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidMobile(mobile) {
        const mobileRegex = /^[0-9+\-\s()]+$/;
        return mobileRegex.test(mobile) && mobile.length >= 10;
    }

    function updateProgressIndicator() {
        for (let i = 1; i <= totalSteps; i++) {
            const stepElement = document.querySelector(`.step[data-step="${i}"]`);
            if (i < currentStep) {
                stepElement.classList.add('completed');
                stepElement.classList.remove('active');
            } else if (i === currentStep) {
                stepElement.classList.add('active');
                stepElement.classList.remove('completed');
            } else {
                stepElement.classList.remove('active', 'completed');
            }
        }
    }

    // Form submission - clear session on successful submit
    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        if (!validateCurrentStep()) {
            e.preventDefault();
            return false;
        }
    });

    // Setup event listeners on page load
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('employeeForm');
        const inputs = form.querySelectorAll('input, select, textarea');

        // Auto-save on input change
        inputs.forEach(input => {
            input.addEventListener('change', autoSaveFormData);
            input.addEventListener('input', autoSaveFormData);
        });

        // Make progress indicator steps clickable
        document.querySelectorAll('.step').forEach(step => {
            step.style.cursor = 'pointer';
            step.addEventListener('click', function() {
                const targetStep = parseInt(this.getAttribute('data-step'));
                navigateToStep(targetStep);
            });
        });

        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Show initial step
        updateProgressIndicator();
    });

    // Optional: Clear session function for testing
    function clearFormSession() {
        if (confirm('Are you sure you want to clear all form data?')) {
            fetch('{{ route('employee.session.clear') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error clearing session:', error);
                });
        }
    }
</script>
