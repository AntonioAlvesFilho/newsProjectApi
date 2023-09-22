<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{

	public function index() {
		return dd('x');
	}

	public function store(CategoryRequest $request) {

		$input = $request->validated();

		$category = Category::create([
			'name' => $request->input('name'),
	]);

		return new CategoryResource($category);
		
	}

}
