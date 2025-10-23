<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="row align-items-center mb-4 border-bottom">
                    <div class="col">
                        <h2 class="mb-1">Profile Settings</h2>
                        <p class="text-muted mb-0">Manage your account settings and preferences</p>
                    </div>
                </div>

                <!-- Profile Information Card -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Password Card -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Card -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
