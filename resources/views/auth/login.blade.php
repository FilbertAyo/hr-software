<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="col-lg-3 col-md-4 col-10 mx-auto text-center card">
        <div class="card-body">


            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h1 class="h4 mb-3">Sign in</h1>

                <!-- Email Address -->
                <div class="form-group">
                    {{-- <label for="email" :value="__('email')" >Email </label> --}}
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        placeholder='Email' required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    {{-- <label for="password" :value="__('password')">Password</label> --}}

                    <x-text-input id="password" class="block mt-1 w-full" placeholder='password' type="password"
                        name="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
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

</x-guest-layout>
