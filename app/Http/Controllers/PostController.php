<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRegisterRequest;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;


class PostController extends Controller
{

	public function index() {
		// invés do take(4), que puxa os primeiros 4 itens apenas. Pode ser usado o slice(4) que puxa a aprtir do 4° item
    $posts = Post::all()->take(4);
    return PostResource::collection($posts);
	}

	public function publicLight() {
	// invés do take(4), que puxa os primeiros 4 itens apenas. Pode ser usado o slice(4) que puxa a aprtir do 4° item
	$posts = Post::all()->take(2);
	return PostResource::collection($posts);
	}

	public function myPosts() {
		return PostResource::collection(auth()->user()->posts);
	}

	public function postView($id)
	{
		$post = Post::with('categories')->find($id);

			if (!$post) {
					return response()->json( 'Post not found', 404);
			}
	
			return response()->json( $post);
	}

	public function postByCategory($category)
{
    
    $categoryName = Category::where('name', $category)->first();

    if (!$categoryName) {
        return response()->json(['message' => 'Categoria não encontrada'], 404);
    }

    // Recupere todos os posts associados à categoria
    $posts = $categoryName->posts;

    return response()->json(['posts' => $posts]);
}

	//Store posts bellow
	public function store(PostRegisterRequest $request) {

		$input = $request->validated();

		$post = auth()->user()->posts()->create([
			'title' => $input['title'], 
			'description' => $input['description'],
			'image' => $input['image'],
		]);		


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

		$category = Category::firstOrCreate(['name' => $input['category']]);
    $post->categories()->attach($category->id);

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
