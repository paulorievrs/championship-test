<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';
    protected $fillable = [
      'name',
      'abbreviation',
      'logo'
    ];

    public function home_matches(): HasMany
    {
        return $this->hasMany(Match::class, 'home_team_id');
    }

    public function guest_matches(): HasMany
    {
        return $this->hasMany(Match::class, 'guest_team_id');
    }

    public function matches()
    {
        return $this->home_matches->merge($this->guest_matches);
    }

}
