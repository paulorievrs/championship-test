<?php
namespace App\Repositories;
use App\Enums\MatchesResultsOperators;
use App\Models\Team;

class TeamRepository
{
    private $model;
    public function __construct(Team $model)
    {
        $this->model = $model;
    }

    public function getAllTeams()
    {
        return $this->model->all();
    }

    public function getTeamMatchesCount(Team $team)
    {
        return $team->matches()->count();
    }

    public function getTeamVictories(Team $team)
    {
        return $this->getTeamMatchesResultsByOperator(MatchesResultsOperators::WIN, $team);
    }

    public function getTeamDraws(Team $team)
    {
        return $this->getTeamMatchesResultsByOperator(MatchesResultsOperators::DRAW, $team);
    }

    public function getTeamDefeats(Team $team)
    {
        return $this->getTeamMatchesResultsByOperator(MatchesResultsOperators::DEFEAT, $team);
    }

    public function getGoalsFor(Team $team)
    {
        $home_matches_goals = $team->home_matches->sum('home_team_score');
        $guest_matches_goals = $team->guest_matches->sum('guest_team_score');
        return $home_matches_goals + $guest_matches_goals;
    }

    public function getGoalsTaken(Team $team)
    {
        $home_matches_goals = $team->home_matches->sum('guest_team_score');
        $guest_matches_goals = $team->guest_matches->sum('home_team_score');
        return $home_matches_goals + $guest_matches_goals;
    }

    public function getTeamGoalDifference(Team $team): int
    {
        $scored = $this->getGoalsFor($team);
        $taken = $this->getGoalsTaken($team);

        return $scored - $taken;
    }

    public function getTeamPoints(Team $team)
    {
        $victories = $this->getTeamVictories($team);
        $draws     = $this->getTeamDraws($team);

        return ( ($victories * 3) + $draws );
    }

    private function getTeamMatchesResultsByOperator(string $operator, Team $team): int
    {
        $home_matches = $team->home_matches()
                        ->whereColumn('home_team_score', $operator, 'guest_team_score')
                        ->get()
                        ->toArray();

        $guest_matches = $team->guest_matches()
                        ->whereColumn('guest_team_score', $operator, 'home_team_score')
                        ->get()
                        ->toArray();

        return sizeof(array_merge($home_matches, $guest_matches));
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }
}
