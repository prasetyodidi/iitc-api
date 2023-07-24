<?php

namespace App\Http\Controllers;

use App\Helpers\PaymentStatus;
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
    public function index()
    {
        $this->authorize('viewAny', Team::class);
        $teams = Team::query()->with([
            'paymentStatus',
            'payment',
        ])->get();
        $teamsResponse = [];
        foreach ($teams as $team) {
            $paymentStatus = isset($team->payment) ? PaymentStatus::PENDING : null;
            $paymentStatus = $team->paymentStatus->status ?? $paymentStatus;
            $teamResponse = [
                'id' => $team->id,
                'name' => $team->name,
                'code' => $team->code,
                'title' => $team->title,
                'isActive' => $paymentStatus,
                'isSubmit' => isset($team->submission),
                'avatar' => $team->avatar,
            ];
            $teamsResponse[] = $teamResponse;
        }

        $responseData = [
            'status' => 1,
            'message' => 'Succeed get detail team',
            'data' => [
                'teams' => $teamsResponse,
            ],
        ];

        return response()->json($responseData, 200);
    }

    public function store(StoreTeamRequest $request, string $competitionSlug): JsonResponse
    {
        $this->authorize('create', Team::class);
        $competition = Competition::query()->where('slug', $competitionSlug)->firstOrFail();
        $code = fake()->bothify('##??##??');
        $teamData = [
            'leader_id' => auth()->id(),
            'competition_id' => $competition->id,
            'code' => $code,
            'name' => $request->name,
        ];

        $team = Team::query()->create($teamData);

        $responseData = [
            'status' => 1,
            'message' => 'Succeed create new team',
            'data' => [
                'team' => [
                    'id' => $team->id,
                    'code' => $team->code,
                    'name' => $team->name,
                ],
            ],
        ];

        return response()->json($responseData, 201);
    }

    public function show(string $teamId): JsonResponse
    {
        $this->authorize('view', Team::query()->find($teamId));
        $team = Team::query()->with([
            'paymentStatus',
            'payment',
            'leader',
            'leader.participant:avatar',
            'members:id,name,email',
            'members.participant:user_id,avatar'
        ])->findOrFail($teamId);
        $paymentStatus = isset($team->payment) ? PaymentStatus::PENDING : null;
        $paymentStatus = $team->paymentStatus->status ?? $paymentStatus;
        $teamResponse = [
            'id' => $team->id,
            'name' => $team->name,
            'code' => $team->code,
            'title' => $team->title,
            'isActive' => $paymentStatus,
            'isSubmit' => isset($team->submission),
            'avatar' => $team->avatar,
            'leader' => [
                'name' => $team->leader->name,
                'email' => $team->leader->email,
                'avatar' => $team->leader->participant->avatar ?? null,
            ],
            'members' => $team->members,
        ];

        $responseData = [
            'status' => 1,
            'message' => 'Succeed get detail team',
            'data' => [
                'team' => $teamResponse,
            ],
        ];

        return response()->json($responseData, 200);
    }

    public function update(UpdateTeamRequest $request, string $teamId): JsonResponse
    {
        $this->authorize('Update', Team::query()->find($teamId));
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
            if ($oldAvatar != null && Storage::exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
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
    }

    public function destroy(string $teamId): JsonResponse
    {
        $this->authorize('delete', Team::query()->find($teamId));
        Team::query()->where('id', $teamId)->delete();

        $responseData = [
            'status' => 1,
            'message' => 'Succeed delete team',
            'data' => [
                'teamId' => $teamId,
            ],
        ];

        return response()->json($responseData, 200);
    }
}
