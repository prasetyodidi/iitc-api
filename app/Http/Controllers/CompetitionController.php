<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionRequest;
use App\Http\Requests\UpdateCompetitionRequest;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Criterion;
use App\Models\TechStack;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $competitions = Competition::query()
                ->with('categories')
                ->get()->map(function (Competition $competition) {
                    $categories = $competition->categories->map(function (Category $category) {
                        return [
                            'name' => $category->name
                        ];
                    });
                    return [
                        'name' => $competition->name,
                        'maxMembers' => $competition->max_members,
                        'categories' => $categories
                    ];
                });

            $responseData = [
                'status' => 1,
                'message' => 'Succeed get all competition',
                'data' => [
                    'competitions' => $competitions,
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

            $arrayCriteria = json_decode($request->criteria);
            $criteriaData = [];
            foreach ($arrayCriteria as $criteria) {
                $criteriaData[] = [
                    'competition_id' => $competition->id,
                    'name' => $criteria->name,
                    'percentage' => $criteria->percentage,
                ];
            }
            $arrayTechStacks = json_decode($request->techStacks);
            $techStacksData = [];
            foreach ($arrayTechStacks as $techStack) {
                $techStacksData[] = [
                    'competition_id' => $competition->id,
                    'name' => $techStack,
                ];
            }
            Criterion::query()->insert($criteriaData);
            TechStack::query()->insert($techStacksData);

            $competition['criteria'] = $criteriaData;
            $competition['techStacks'] = $techStacksData;
            $responseData = [
                'status' => 1,
                'message' => 'Succeed create new competition',
                'data' => [
                    'competition' => $competition,
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

    public function show(string $slug): JsonResponse
    {
        try {
            $competition = Competition::with(['criteria:id,competition_id,name', 'techStacks:id,competition_id,name'])
                ->where('slug', $slug)
                ->firstOrFail();

            $responseData = [
                'status' => 1,
                'message' => 'Succeed get detail competition',
                'data' => [
                    'competition' => $competition,
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

    public function update(UpdateCompetitionRequest $request, string $slug): JsonResponse
    {
        try {
            $competition = Competition::query()->where('slug', $slug)->firstOrFail();

            $cover = $request->file('cover')->store('competition/avatar', ['disk' => 'public']);
            Storage::disk('public')->delete($competition->cover);

            $competitionData = [
                'name' => $request->input('name'),
                'deadline' => $request->input('deadline'),
                'max_members' => $request->input('maxMembers'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
                'guide_book' => $request->input('guideBookLink'),
                'cover' => $cover,
            ];

            $competition->update($competitionData);

            Criterion::query()->where('competition_id', $competition->id)->delete();
            TechStack::query()->where('competition_id', $competition->id)->delete();

            $criteriaData = $this->getCriteriaToDatabase(json_decode($request->criteria), $competition->id);
            $techStacksData = $this->getTechStacksToDatabase(json_decode($request->techStacks), $competition->id);

            Criterion::query()->insert($criteriaData);
            TechStack::query()->insert($techStacksData);

            $competition['criteria'] = $criteriaData;
            $competition['techStacks'] = $techStacksData;
            $responseData = [
                'status' => 1,
                'message' => 'Succeed update competition',
                'data' => [
                    'competition' => $competition,
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

    private function getCriteriaToDatabase(array $criteria, int $competitionId): array
    {
        $criteriaData = [];
        foreach ($criteria as $criterion) {
            $criteriaData[] = [
                'competition_id' => $competitionId,
                'name' => $criterion->name,
                'percentage' => $criterion->percentage,
            ];
        }

        return $criteriaData;
    }

    private function getTechStacksToDatabase(array $techStacks, int $competitionId): array
    {
        $techStacksData = [];
        foreach ($techStacks as $techStack) {
            $techStacksData[] = [
                'competition_id' => $competitionId,
                'name' => $techStack,
            ];
        }

        return $techStacksData;
    }

    public function destroy(string $slug): JsonResponse
    {
        try {
            $competition = Competition::query()->where('slug', $slug)->firstOrFail();
            $competition->delete();

            $responseData = [
                'status' => 1,
                'message' => 'Succeed delete competition',
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
