<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function home_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function guest_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'guest_team_id');
    }
}
