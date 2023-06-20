<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionCategoryRequest;
use App\Http\Requests\UpdateCompetitionCategoryRequest;
use App\Models\CompetitionCategory;
use Exception;

class CompetitionCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = CompetitionCategory::all();

            $responseData = [
                'status' => 1,
                'message' => 'Succeed gel all competition categories',
                'data' => [
                    'categories' => $categories,
                ],
            ];

            return response()->json($responseData, 200);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => 'Failed get all competition categories',
            ];

            return response()->json($responseData, 400);
        }
    }

    public function store(StoreCompetitionCategoryRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
            ];

            $category = CompetitionCategory::query()->create($data);

            $responseData = [
                'status' => 0,
                'message' => 'Succeed create new competition category',
                'data' => [
                    'category' => $category,
                ],
            ];

            return response()->json($responseData, 201);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => 'Failed create new competition category',
            ];

            return response()->json($responseData, 400);
        }
    }

    public function update(UpdateCompetitionCategoryRequest $request, string $categoryId)
    {
        try {
            $competitionCategory = CompetitionCategory::where('id', $categoryId)->firstOrFail();


            $data = [
                'name' => $request->name,
            ];

            $competitionCategory->update($data);

            $responseData = [
                'status' => 0,
                'message' => 'Succeed create new competition category',
                'data' => [
                    'category' => $competitionCategory,
                ],
            ];

            return response()->json($responseData, 200);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => 'Failed update competition category',
            ];

            return response()->json($responseData, 400);
        }
    }
}
