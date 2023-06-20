<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionCategoryRequest;
use App\Models\CompetitionCategory;
use Exception;
use Illuminate\Http\Request;

class CompetitionCategoryController extends Controller
{
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
}
