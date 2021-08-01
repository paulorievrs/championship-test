<?php
namespace App\Repositories;
use App\Enums\MatchesResultsOperators;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    private $model;
    public function __construct(Team $model)
    {
        $this->model = $model;
    }

    /**
     * Get all the teams from the database
     * @return Team[]|Collection
     */
    public function getAllTeams(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get how many matches the team played
     * @param Team $team
     * @return int
     */
    public function getTeamMatchesCount(Team $team): int
    {
        return $team->matches()->count();
    }

    /**
     * Get how many victories the team have
     * @param Team $team
     * @return int
     */
    public function getTeamVictories(Team $team): int
    {
        return $this->getTeamMatchesResultsByOperator($team, MatchesResultsOperators::WIN);
    }

    /**
     * Get how many draws the team have
     * @param Team $team
     * @return int
     */
    public function getTeamDraws(Team $team): int
    {
        return $this->getTeamMatchesResultsByOperator($team, MatchesResultsOperators::DRAW);
    }

    /**
     * Get how many defeats the team have
     * @param Team $team
     * @return int
     */
    public function getTeamDefeats(Team $team): int
    {
        return $this->getTeamMatchesResultsByOperator($team, MatchesResultsOperators::DEFEAT);
    }

    /**
     * Get how many goals for the team made
     * @param Team $team
     * @return int
     */
    public function getGoalsFor(Team $team): int
    {
        $home_matches_goals = $team->home_matches->sum('home_team_score');
        $guest_matches_goals = $team->guest_matches->sum('guest_team_score');
        return $home_matches_goals + $guest_matches_goals;
    }

    /**
     * Get how many goals the team has taken
     * @param Team $team
     * @return int
     */
    public function getGoalsTaken(Team $team): int
    {
        $home_matches_goals = $team->home_matches->sum('guest_team_score');
        $guest_matches_goals = $team->guest_matches->sum('home_team_score');
        return $home_matches_goals + $guest_matches_goals;
    }

    /**
     * Get the goal difference from the teams
     * @param Team $team
     * @return int
     */
    public function getTeamGoalDifference(Team $team): int
    {
        $scored = $this->getGoalsFor($team);
        $taken = $this->getGoalsTaken($team);

        return $scored - $taken;
    }

    /**
     * Get how many points the team have
     * @param Team $team
     * @return int
     */
    public function getTeamPoints(Team $team): int
    {
        $victories = $this->getTeamVictories($team);
        $draws     = $this->getTeamDraws($team);

        return ( ($victories * 3) + $draws );
    }

    /**
     * Get the matches with a comparative operator
     * If the operator is >, will return the win matches
     * If the operator is <, will return the loose matches
     * If the operator is =, will return the draw matches
     * Can be found this Enum at Enums/MatchesResultsOperators
     * @param string $operator
     * @param Team $team
     * @return int
     */
    private function getTeamMatchesResultsByOperator(Team $team, string $operator = MatchesResultsOperators::WIN): int
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

    public function find(int $id): Team
    {
        return $this->model->find($id);
    }

    /**
     * Get the teams that only haven't reached the limit of matches
     * @param object $teams
     * @param int $max
     * @return array
     */
    public function getTeamsThatHaventReachTheLimitOfMatches(object $teams, int $max = 38): array
    {
        $filteredTeams = [];
        foreach ($teams as $team) {
            $matchesCount = $this->getTeamMatchesCount($team);
            if($matchesCount < $max) {
                unset($team->home_matches);
                unset($team->guest_matches);
                $team->matchesCount = $matchesCount;
                $filteredTeams[] = $team;
            }
        }

        return $filteredTeams;
    }
}
