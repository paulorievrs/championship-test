<?php

namespace App\Repositories;

use App\Models\Match;

class MatchRepository
{
    private $model;
    private $teamRepository;

    public function __construct(Match $model, TeamRepository $teamRepository)
    {
        $this->model = $model;
        $this->teamRepository = $teamRepository;

    }

    public function createMatch(array $match): Match
    {
        return $this->model->create($match);
    }

    /**
     * Verify if the data has valid properties (the two teams must be different and the teams may play maximum of 38 matches)
     * @param array $data
     * @return string[]|null
     */
    public function isDataInvalid(array $data): ?array
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
