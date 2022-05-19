<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidateanswer extends Model
{
    use HasFactory;
    protected $table = 'candidateanswers';
    protected $fillable = ['candidate_id','answer_id','description'];
}
