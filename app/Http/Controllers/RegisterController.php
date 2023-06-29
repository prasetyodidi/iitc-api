<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(StoreRegisterRequest $request)
    {
        try {
            $data = [
                "name" => $request->fullName,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "phone" => $request->phone,
                "avatar" => 'user/avatar/default.png',
            ];
            $user = User::create($data);

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
        } catch (Exception $exception) {
            $data = [
                "status" => 0,
                "message" => $exception->getMessage(),
            ];
            return response()->json($data, 400);
        }
    }
}
