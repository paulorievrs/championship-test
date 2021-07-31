<?php

namespace Feature\App\Http\Controllers;

use App\Models\Match;
use App\Models\Team;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamsControllerTest extends TestCase
{
    use WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_select_teams()
    {
        $response = $this->get('/teams/select');
        $response->assertStatus(200);

        $teams = json_decode($response->content(), true)['teams'];
        foreach ($teams as $team) {
            $this->assertTrue($team['matchesCount'] < 37);
        }

    }
}
