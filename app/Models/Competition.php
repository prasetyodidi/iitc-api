<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function competitionCompetitionCategories(): HasMany
    {
        return $this->hasMany(CompetitionCompetitionCategory::class, 'competition_id');
    }

    public function techStacks(): HasMany
    {
        return $this->hasMany(TechStack::class, 'competition_id');
    }

    public function criterias(): HasMany
    {
        return $this->hasMany(Criteria::class, 'competition_id');
    }
}
