<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="col-lg-3 col-md-4 col-10 mx-auto card">
        <div class="card-body">


            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h1 class="h4 mb-3 text-center">Sign in</h1>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter your email" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" name="password" placeholder="Enter your password"
                        required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Company Selection -->
                <div class="form-group">
                    <label for="company_id">Company</label>
                    <select id="company_id" name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                        <option value="">Select Company</option>
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const companySelect = document.getElementById('company_id');

            if (email) {
                // Clear existing options
                companySelect.innerHTML = '<option value="">Loading companies...</option>';

                // Fetch companies for this email
                fetch('/api/user-companies', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    companySelect.innerHTML = '<option value="">Select Company</option>';

                    if (data.companies && data.companies.length > 0) {
                        data.companies.forEach(company => {
                            const option = document.createElement('option');
                            option.value = company.id;
                            option.textContent = company.company_name;
                            companySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No companies found for this email';
                        option.disabled = true;
                        companySelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error loading companies:', error);
                    companySelect.innerHTML = '<option value="">Error loading companies - Check console for details</option>';
                });
            } else {
                companySelect.innerHTML = '<option value="">Select Company</option>';
            }
        });
    </script>

</x-guest-layout>
