<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRegisterRequest;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use Illuminate\Support\Str;


class PostController extends Controller
{

	public function public() {
		// invés do take(4), que puxa os primeiros 4 itens apenas. Pode ser usado o slice(4) que puxa a aprtir do 4° item
    $posts = Post::all()->take(4);
    return PostResource::collection($posts);
	}

	public function publicLight() {
	// invés do take(4), que puxa os primeiros 4 itens apenas. Pode ser usado o slice(4) que puxa a aprtir do 4° item
	$posts = Post::all()->take(2);
	return PostResource::collection($posts);
	}

	public function index() {
		return PostResource::collection(auth()->user()->posts);
	}

	public function post($url)
	{

			$post = Post::where('url', $url)->first();

			if (!$post) {
					return response()->json( 'Post not found', 404);
			}
	
			return response()->json( $post);
	}

	public function store(PostRegisterRequest $request) {
		
		$input = $request->validated();

		$post = auth()->user()->posts()->create($input);		

		$postId = $post->id;

		$url = $postId . '-' . Str::slug($input['title']);

		$post->url = $url;

				 // Check if a image is present in the request

		if ($request->hasFile('image')) {
			$image = $request->file('image');

			// Upload the image to S3
			$path = Storage::disk('s3')->put('uploads', $image);

			// Generate the S3 URL
			$s3Url = Storage::disk('s3')->url($path);

			// Update the input to include the S3 URL
			$post->image = $s3Url;
		}

		$post->save();

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
