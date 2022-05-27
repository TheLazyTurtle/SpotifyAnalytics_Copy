<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'spotify_id' => 'required|max:100',
            'first_name' => 'required|min:4',
            'last_name' => 'required|min:4',
            'username' => 'required|min:4|max:20',
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);

        $user = User::create([
            'spotify_id' => $request->spotify_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->viaRemember()) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            setcookie("test", $token);
            return response()->json(['token' => $token], 200);
        } else if (auth()->attempt($data, true)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
