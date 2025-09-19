<?php

namespace App\Http\Controllers;

use App\Models\Mainstation;
use App\Models\Substation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{

    public function home()
    {

        $targetDate = '2025-04-23 00:00:00';
        $substation = Substation::count();
        $mainstation = Mainstation::count();
        $user =  User::count();
        return view('dashboard',compact('substation','mainstation','user','targetDate'));
    }

    public function user()
    {

        $user= User::all();
        return view('users.index',compact('user'));
    }

    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed',Rules\Password::defaults()->min(6),],
        ]);

        $user = User::create([
            'name' => $request->name,
            'status' => $request->status,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->back()->with('success','New user added successfully');
    }

public function destroy($id)
{
    $user = User::find($id);

    if ($user) {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    } else {
        return redirect()->back()->with('error', 'User not found');
    }
}

}
