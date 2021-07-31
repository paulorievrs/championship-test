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

    private function removeTeamsThatHasMaximumMatches(object $teams, int $max = 38): array
    {
        $filteredTeams = [];
        foreach ($teams as $team) {
            $matchesCount = $this->repository->getTeamMatchesCount($team);
            if($matchesCount < $max) {
                unset($team->home_matches);
                unset($team->guest_matches);
                $team->matchesCount = $matchesCount;
                $filteredTeams[] = $team;
            }
        }

        return $filteredTeams;
    }

    public function getTeamsTable(): array
    {

        $teams = $this->repository->getAllTeams();

        foreach ($teams as $team) {
            $team->defeats = $this->repository->getTeamDefeats($team);

            $team->victories = $this->repository->getTeamVictories($team);
            $team->matches = $this->repository->getTeamMatchesCount($team);
            $team->draws = $this->repository->getTeamDraws($team);

            $team->goalsFor = $this->repository->getGoalsFor($team);
            $team->goalsTaken = $this->repository->getGoalsTaken($team);
            $team->goalDifference = $this->repository->getTeamGoalDifference($team);
            $team->points = $this->repository->getTeamPoints($team);
        }

        $teams = $teams->toArray();

        usort($teams, fn ($item1, $item2): int =>
            [
                $item2['points'],
                $item2['matches'],
                $item2['victories'],
                $item2['draws'],
                $item2['defeats'],
                $item2['goalsFor'],
                $item2['goalsTaken'],
                $item2['goalDifference']
            ]
            <=>
            [
                $item1['points'],
                $item1['matches'],
                $item1['victories'],
                $item1['draws'],
                $item1['defeats'],
                $item1['goalsFor'],
                $item1['goalsTaken'],
                $item1['goalDifference']
            ]
        );

        return [
            'teams' => $teams
        ];

    }

}
