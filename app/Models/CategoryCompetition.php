<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCompetition extends Model
{
    use HasFactory;

    protected $table = 'category_competition';

    protected $guarded = [];
}
