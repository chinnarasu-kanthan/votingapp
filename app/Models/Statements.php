<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statements extends Model
{
    use HasFactory;
    protected $fillable = ['candidate_id','statement', 'type', 'point', 'status'];
}
