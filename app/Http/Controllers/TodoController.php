<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use App\Http\Requests\TodoRegisterRequest;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index() {
			return TodoResource::collection(auth()->user()->todos);
		}

		public function store(TodoRegisterRequest $request) {
			
			$input = $request->validated();
			$todo = auth()->user()->todos()->create($input);
			return new TodoResource($todo);
		}

		public function update (Todo $todo, TodoRegisterRequest $request) {
			$input = $request->validated();

			$todo->fill($input);
			$todo->save();

			return new TodoResource($todo->fresh());
		}

		public function destroy(Todo $todo) {
			$todo->delete();
		}
}
