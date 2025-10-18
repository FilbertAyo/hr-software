<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API route for getting user companies (no auth required for login)
Route::post('/user-companies', function(Request $request) {
    try {
        $request->validate([
            'email' => 'required|email'
        ]);

        \Log::info('API Request - User Companies', [
            'email' => $request->email,
            'request_data' => $request->all()
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            $companies = $user->companies()->get(['companies.id', 'companies.company_name']);
            \Log::info('User found with companies', [
                'user_id' => $user->id,
                'companies_count' => $companies->count(),
                'companies' => $companies->toArray()
            ]);
            return response()->json(['companies' => $companies]);
        }

        \Log::info('No user found for email', ['email' => $request->email]);
        return response()->json(['companies' => []]);
    } catch (\Exception $e) {
        \Log::error('Error in user-companies API', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        return response()->json(['error' => 'Internal server error'], 500);
    }
});
