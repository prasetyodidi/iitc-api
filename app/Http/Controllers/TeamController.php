<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Competition;
use App\Models\Team;
use Exception;

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
    public function store(StoreTeamRequest $request, string $competitionSlug)
    {
        try {
            $competition = Competition::query()->where('slug', $competitionSlug)->firstOrFail();
            $avatar = $request->file('avatar')->store('team/avatar', ['disk' => 'public']);
            $teamData = [
                'leader_id' => auth()->id(),
                'competition_id' => $competition->id,
                'title' => $request->title,
                'name' => $request->name,
                'avatar' => $avatar,
            ];

            $team = Team::query()->create($teamData);

            $responseData = [
                'status' => 1,
                'message' => 'Succeed create new team',
                'data' => [
                    'team' => $team,
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

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
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
