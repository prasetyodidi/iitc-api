<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Competition;
use App\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
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
     * Store a newly created resource in storage.
     */
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
                'avatar' => $avatar,
            ];

            $team = Team::query()->create($teamData);

            $responseData = [
                'status' => 1,
                'message' => 'Succeed create new team',
                'data' => [
                    'team' => [
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
                'message' => $exception->getMessage() . ":" . get_class($exception),
            ];

            return response()->json($responseData, 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $teamId)
    {
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
        try {
            $responseData = [
                'status' => 1,
                'message' => 'Succeed create new team',
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}
