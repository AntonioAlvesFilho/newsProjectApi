<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\MeUpdateRequest;
use App\Services\UserService;

class MeController extends Controller
{

	public function __construct() {

		$this->middleware('auth:api');

	}
	
	public function index()
	{
		return new UserResource(auth()->user());
	}

	public function update(MeUpdateRequest $request)
	{
		$input = $request->validated();
		$user =	(new UserService())->update(auth()->user(), $input);
		return new UserResource($user);
	}
} 
