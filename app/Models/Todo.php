<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TodoTask;

class Todo extends Model
{
	use HasFactory;
	
  	protected $fillable = [
			'label',
		];
		//usado na criação do Factory
		public function tasks() {
			return $this->hasMany(TodoTask::class) ;
		}
}
