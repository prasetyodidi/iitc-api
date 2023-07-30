<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthenticationException;
use App\Http\Requests\StoreLoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(StoreLoginRequest $request)
    {
        $user = User::where("email", $request->email)->firstOrFail();
        if (!Hash::check($request->password, $user->password)) {
            throw new AuthenticationException("Invalid Password");
        }

        $token = $user->createToken('authToken')->plainTextToken;

        $data = [
            "status" => 1,
            "message" => "berhasil login",
            "data" => [
                "access_token" => $token
            ]
        ];
        return response()->json($data, 200);
    }
}
