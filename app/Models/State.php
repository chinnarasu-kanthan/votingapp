<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'states';

    protected $fillable = ['state_name',"status"];
}
