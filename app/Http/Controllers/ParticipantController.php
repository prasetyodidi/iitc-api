<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Participant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends Controller
{
    public function update(UpdateParticipantRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $userData = [
                'name' => $request->input('fullName'),
                'phone' => $request->input('phone'),
            ];
            $user->update($userData);

            $avatar = $request->file('avatar')->store('participant/avatar', ['disk' => 'public']);
            $photoIdentity = $request->file('photoIdentity')->store('participant/photo-identity', ['disk' => 'public']);
            $twibbon = $request->file('twibbon')->store('participant/twibbon', ['disk' => 'public']);
            $profileData = [
                'grade' => $request->input('grade'),
                'institution' => $request->input('institution'),
                'student_id_number' => $request->input('studentId'),
                'gender' => $request->input('gender'),
                'photo_identity' => Storage::disk('public')->url($photoIdentity),
                'avatar' => Storage::disk('public')->url($avatar),
                'twibbon' =>Storage::disk('public')->url($twibbon),
            ];
            $detail = Participant::query()->updateOrCreate(['user_id' => auth()->id()], $profileData);

            $responseData = [
                'status' => 1,
                'message' => 'Succeed update user profile',
                'data' => [
                    'user' => $user,
                    'detail' => $detail,
                ],
            ];

            return response()->json($responseData, 200);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => $exception->getMessage(),
            ];

            return response()->json($responseData, 400);
        }
    }
}
