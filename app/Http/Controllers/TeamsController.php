<?php

namespace App\Http\Controllers;

use App\Repositories\TeamRepository;
class TeamsController extends Controller
{
    private $repository;
    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getTeams(): array
    {
        $teams = $this->repository->getAllTeams();
        $teams = $this->removeTeamsThatHasMaximumMatches($teams);
        return [
            'teams' => $teams
        ];
    }

    private function removeTeamsThatHasMaximumMatches(object $teams, int $max = 38): object
    {
        foreach ($teams as $team) {
            $matchesCount = $this->repository->getTeamMatchesCount($team);
            if($matchesCount >= $max) {
                unset($team);
            }
        }

        return $teams;
    }

    public function getTeamsTable(): array
    {
        $teams = $this->repository->getAllTeams();

        foreach ($teams as $team) {
            $team->defeats = $this->repository->getTeamDefeats($team)->count();

            $team->victories = $this->repository->getTeamVictories($team)->count();
            $team->matches = $this->repository->getTeamMatchesCount($team);
            $team->draws = $this->repository->getTeamDraws($team)->count();

            $team->goalsFor = $this->repository->getGoalsFor($team);
            $team->goalsTaken = $this->repository->getGoalsTaken($team);
            $team->goalDifference = $this->repository->getTeamGoalDifference($team);
            $team->points = $this->repository->getTeamPoints($team);
        }

        return [
            'teams' => $teams
        ];

    }

}
