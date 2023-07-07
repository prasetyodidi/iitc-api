<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Participant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParticipantRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $userData = [
                'name' => $request->input('fullName'),
                'phone' => $request->input('phone'),
            ];
            $user->update($userData);

            $avatar = $request->file('avatar')->store('user/avatar', ['disk' => 'public']);
            $photoIdentity = $request->file('photoIdentity')->store('participant', ['disk' => 'local']);
            $profileData = [
                'grade' => $request->input('grade'),
                'institution' => $request->input('institution'),
                'student_id_number' => $request->input('studentId'),
                'gender' => $request->input('gender'),
                'photo_identity' => $photoIdentity,
                'avatar' => url('/') . Storage::url($avatar),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        //
    }
}
