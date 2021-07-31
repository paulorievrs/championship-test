<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMatchRequest;
use App\Repositories\MatchRepository;
use App\Repositories\TeamRepository;

class MatchesController extends Controller
{
    private $repository;
    private $teamRepository;
    public function __construct(MatchRepository $repository, TeamRepository $teamRepository)
    {
        $this->repository = $repository;
        $this->teamRepository = $teamRepository;
    }

    public function createMatch(CreateMatchRequest $request)
    {
        $payload = $request->only([
            'home_team_id',
            'guest_team_id',
            'home_team_score',
            'guest_team_score'
        ]);

        $isDataInvalid = $this->isDataInvalid($payload);
        if($isDataInvalid) {
            return $isDataInvalid;
        }

        return $this->repository->createMatch($payload);

    }

    private function isDataInvalid(array $data)
    {
        if($data['home_team_id'] === $data['guest_team_id']) {
            return [
                'error' => 'The home_team_id must be different of guest_team_id'
            ];
        }

        $home_team = $this->teamRepository->find($data['home_team_id']);
        $guest_team = $this->teamRepository->find($data['guest_team_id']);

        $home_team_matches = $this->teamRepository->getTeamMatchesCount($home_team);
        $guest_team_matches = $this->teamRepository->getTeamMatchesCount($guest_team);

        if($home_team_matches >= 38 || $guest_team_matches >= 38) {
            return [
                'error' => 'The teams must play only 38 matches'
            ];
        }

        return null;

    }

}
