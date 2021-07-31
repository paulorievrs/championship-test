<?php

namespace Feature\App\Http\Controllers;

use App\Models\Match;
use App\Models\Team;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MatchesControllerTest extends TestCase
{
    use WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_match()
    {
        $matchData = [
            'home_team_id' => Team::all()->random()->id,
            'guest_team_id' => Team::all()->random()->id,
            'home_team_score' => $this->faker->numberBetween(0, 10),
            'guest_team_score' => $this->faker->numberBetween(0, 10)
        ];
        $response = $this->post('/matches/create', $matchData);
        $response->assertStatus(201);

        $createdMatch = json_decode($response->content(), true);
        $this->assertDatabaseHas('matches', $createdMatch);

    }
}
