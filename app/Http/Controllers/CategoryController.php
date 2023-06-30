<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::all();

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

    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
            ];

            $category = Category::query()->create($data);

            $responseData = [
                'status' => 1,
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

    public function update(UpdateCategoryRequest $request, string $categoryId): JsonResponse
    {
        try {
            $competitionCategory = Category::where('id', $categoryId)->firstOrFail();


            $data = [
                'name' => $request->name,
            ];

            $competitionCategory->update($data);

            $responseData = [
                'status' => 1,
                'message' => 'Succeed update competition category',
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

    public function destroy(string $categoryId): JsonResponse
    {
        try {
            $competitionCategory = Category::where('id', $categoryId)->firstOrFail();

            $competitionCategory->delete();

            $responseData = [
                'status' => 1,
                'message' => 'Succeed delete competition category',
                'data' => [
                    'category' => $competitionCategory,
                ],
            ];

            return response()->json($responseData, 200);
        } catch (Exception $exception) {
            $responseData = [
                'status' => 0,
                'message' => 'Failed delete competition category',
            ];

            return response()->json($responseData, 400);
        }
    }
}
