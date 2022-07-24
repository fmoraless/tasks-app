<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        $user = User::whereEmail($request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
               'email' => ['The provided credentials are incorrect.']
            ]);
        }

         /* Generarción del token */
        $plainTextToken = $user->createToken($request->device_name)->plainTextToken;
        //dd($plainTextToken);
        return response()->json([
            'plain-text-token' => $plainTextToken
        ]);

    }
}
