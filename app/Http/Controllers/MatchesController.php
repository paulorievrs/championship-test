<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMatchRequest;
use App\Repositories\MatchRepository;
use Illuminate\Http\JsonResponse;

class MatchesController extends Controller
{
    private $repository;
    public function __construct(MatchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a match
     * @param CreateMatchRequest $request
     * @return JsonResponse
     */
    public function createMatch(CreateMatchRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $isDataInvalid = $this->repository->isDataInvalid($payload);
        if($isDataInvalid) {
            return response()->json(['message' => $isDataInvalid]);
        }

        $createdMatch = $this->repository->createMatch($payload);

        return response()->json($createdMatch, 201);

    }



}
