<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => ['required','email', 'unique:users'],
            'password' => ['required'],
            'device_name' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $plainTextToken = $user->createToken($request->device_name)->plainTextToken;
        //dd($plainTextToken);
        return response()->json([
            'plain-text-token' => $plainTextToken
        ]);
    }
}
