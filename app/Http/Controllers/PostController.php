<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRegisterRequest;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;


class PostController extends Controller
{

	public function public() {
    $posts = Post::all();
    return PostResource::collection($posts);
}

	public function index() {
		return PostResource::collection(auth()->user()->posts);
	}

	public function store(PostRegisterRequest $request) {
		
		$input = $request->validated();

		 // Check if a image is present in the request
		 if ($request->hasFile('image')) {
			$image = $request->file('image');

			// Upload the image to S3
			$path = Storage::disk('s3')->put('uploads', $image);

			// Generate the S3 URL
			$s3Url = Storage::disk('s3')->url($path);

			// Update the input to include the S3 URL
			$input['image'] = $s3Url;
	}

		$post = auth()->user()->posts()->create($input);
		return new PostResource($post);
	}

	public function update (Post $post, PostRegisterRequest $request) {
		$input = $request->validated();

		$post->fill($input);
		$post->save();

		return new PostResource($post->fresh());
	}

	public function destroy(Post $post) {
		$post->delete();
	}
}
