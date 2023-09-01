<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TodoTaskResource;
use App\Http\Requests\TaskRequest;

class TodoTaskController extends Controller
{
	public function index(TaskRequest $request) {
		$input = $request->validated();

		return TodoTaskResource::collection(auth()->user()->tasks->where('todo_id', $input['id']));
	}

}
