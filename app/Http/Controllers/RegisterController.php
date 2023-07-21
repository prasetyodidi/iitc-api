<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(StoreRegisterRequest $request): JsonResponse
    {
        $data = [
            "name" => $request->fullName,
            "email" => $request->email,
            "password" => $request->password,
            "phone" => $request->phone,
        ];
        $user = User::query()->create($data);
        $user->assignRole('User');

        $responseData = [
            "status" => 1,
            "message" => "Success registering new user",
            "data" => [
                "user" => [
                    "id" => $user->id,
                    "fullName" => $user->name,
                    "email" => $user->email,
                ],
            ],
        ];

        return response()->json($responseData, 201);
    }
}
