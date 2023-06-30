<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionRequest;
use App\Http\Requests\UpdateCompetitionRequest;
use App\Models\Competition;
use Exception;
use Illuminate\Http\JsonResponse;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(StoreCompetitionRequest $request): JsonResponse
    {
        try {
            $cover = $request->file('cover')->store('competition/avatar', ['disk' => 'public']);
            $competitionData = [
                'name' => $request->input('name'),
                'deadline' => $request->input('deadline'),
                'max_members' => $request->input('maxMembers'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
                'guide_book' => $request->input('guideBookLink'),
                'cover' => $cover,
            ];

            $competition = Competition::query()->create($competitionData);

            $responseData = [
                'status' => 0,
                'message' => 'Succeed create new competition',
                'data' => [
                    'category' => $competition,
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
    public function show(Competition $competition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competition $competition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetitionRequest $request, Competition $competition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competition $competition)
    {
        //
    }
}
