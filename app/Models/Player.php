<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
	
	protected $fillable = ['firstName','lastName','team_id','playerImageURI'];
	
	public function team()
    {
    	return $this->belongsTo(Team::class);
    }
}
