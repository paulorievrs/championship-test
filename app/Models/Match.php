<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Match extends Model
{
    use HasFactory, Uuids;
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'home_team_id',
        'guest_team_id',
        'home_team_score',
        'guest_team_score'
    ];
}
