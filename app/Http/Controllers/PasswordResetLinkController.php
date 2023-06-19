<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasswordResetLinkRequest;
use App\Models\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function store(StorePasswordResetLinkRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $token = Password::broker()->createToken($user);

        $responseData = [
            "status" => 1,
            "message" => "Success request link reset password",
            "data" => [
                "token_reset_password" => $token,
            ],
        ];

        return response()->json($responseData);
    }
}
