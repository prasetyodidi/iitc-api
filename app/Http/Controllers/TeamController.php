<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Competition;
use App\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(StoreTeamRequest $request, string $competitionSlug): JsonResponse
    {
        try {
            $competition = Competition::query()->where('slug', $competitionSlug)->firstOrFail();
            $avatar = $request->file('avatar')->store('team/avatar', ['disk' => 'public']);
            $code = fake()->bothify('##??##??');
            $teamData = [
                'leader_id' => auth()->id(),
                'competition_id' => $competition->id,
                'code' => $code,
                'title' => $request->title,
                'name' => $request->name,
                'avatar' => url('/') . Storage::url($avatar),
            ];

            $team = Team::query()->create($teamData);

            $responseData = [
                'status' => 1,
                'message' => 'Succeed create new team',
                'data' => [
                    'team' => [
                        'id' => $team->id,
                        'code' => $team->code,
                        'title' => $team->title,
                        'name' => $team->name,
                    ],
                ],
            ];

            return response()->json($responseData, 201);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => $exception->getMessage(),
            ];

            return response()->json($responseData, 400);
        }
    }

    public function show(string $teamId): JsonResponse
    {
        try {
            $team = Team::query()->with('leader')->findOrFail($teamId);
            $teamResponse = [
                'name' => $team->name,
                'code' => $team->code,
                'title' => $team->title,
                'isActive' => $team->is_active ? 'Pending' : 'Active',
                'isSubmit' => isset($team->submission),
                'avatar' => $team->avatar,
                'leader' => ['name' => $team->leader->name],
            ];

            $responseData = [
                'status' => 1,
                'message' => 'Succeed get detail team',
                'data' => [
                    'team' => $teamResponse,
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

    public function update(UpdateTeamRequest $request, string $teamId): JsonResponse
    {
        try {
            $team = Team::query()->findOrFail($teamId);
            $teamData = [
                'name' => $request->name,
                'title' => $request->title,
            ];
            $isUploadAvatar = $request->file('avatar') !== null;
            if ($isUploadAvatar) {
                $oldAvatar = $team->avatar;
                $avatar = $request->file('avatar')->store('team/avatar', ['disk' => 'public']);
                $teamData['avatar'] = url('/') . Storage::url($avatar);
                Storage::disk('public')->delete($oldAvatar);
            }
            $isUploadSubmission = $request->file('submission') !== null;
            if ($isUploadSubmission) {
                $uuidFolder = Str::uuid();
                $originalFileName = $request->file('submission')->getClientOriginalName();
                $submission = $request->file('submission')
                    ->storeAs(
                        "submission/$uuidFolder",
                        $originalFileName,
                        ['disk' => 'local']
                    );
                $teamData['submission'] = $submission;
                $teamData['submission_file_name'] = $originalFileName;
            }

            $team->update($teamData);

            $responseData = [
                'status' => 1,
                'message' => 'Succeed updated team',
                'data' => [
                    'team' => [
                        'id' => $team->id,
                        'name' => $team->name,
                        'title' => $team->title,
                        'avatar' => $team->avatar,
                    ],
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

    public function destroy(string $teamId): JsonResponse
    {
        try {
            Team::query()->where('id', $teamId)->delete();

            $responseData = [
                'status' => 1,
                'message' => 'Succeed delete team',
                'data' => [
                    'teamId' => $teamId,
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
