<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): \Illuminate\Http\JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $responseData = [
                "status" => 1,
                "message" => "User is verified",
            ];

            return response()->json($responseData, 200);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $responseData = [
            "status" => 1,
            "message" => "User has been verified",
        ];

        return response()->json($responseData, 200);
    }
}
