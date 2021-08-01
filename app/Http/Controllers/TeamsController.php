<?php

namespace App\Http\Controllers;

use App\Repositories\TeamRepository;
use Illuminate\Http\JsonResponse;

class TeamsController extends Controller
{
    private $repository;
    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all teams filtering them with the maximum quantity of the matches that they can have
     * @return JsonResponse
     */
    public function getTeams(): JsonResponse
    {
        $teams = $this->repository->getAllTeams();
        $teams = $this->repository->getTeamsThatHaventReachTheLimitOfMatches($teams);
        return response()->json([
            'teams' => $teams
        ]);
    }



    /**
     * Get the teams for the main table with their own statistics and sorting them
     * @return JsonResponse
     */
    public function getTeamsTable(): JsonResponse
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

        return response()->json([
            'teams' => $teams
        ]);

    }

}
