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

    /**
     * Create a match
     * @param CreateMatchRequest $request
     * @return object
     */
    public function createMatch(CreateMatchRequest $request): object
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

    /**
     * Verify if the data has valid properties (the two teams must be different and the teams may play maximum of 38 matches)
     * @param array $data
     * @return string[]|null
     */
    private function isDataInvalid(array $data): ?array
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
