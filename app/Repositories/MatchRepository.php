<?php

namespace App\Repositories;

use App\Models\Match;

class MatchRepository
{
    private $model;
    public function __construct(Match $model)
    {
        $this->model = $model;
    }

    public function createMatch(array $match)
    {
        return $this->model->create($match);
    }
}
