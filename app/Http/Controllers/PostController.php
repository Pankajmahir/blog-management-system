<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(12); // You can adjust the number of posts per page
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $comments = $post->comments;
        return view('posts.show', compact('post', 'comments'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function edit(Post $post)
    {
        if (auth()->user()->id === $post->user_id) {
            return view('posts.edit', compact('post'));
        } else {
            return redirect()->route('posts.index');
        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new post
        $post = new Post();
        $post->user_id =  Auth::id();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return response()->json(['message' => 'Post created successfully'], 200);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = Post::findOrFail($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return response()->json(['message' => 'Post updated successfully'], 200);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }


    public function storeCommant(Request $request)
    {
        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->content = $request->content;
        $comment->save();

        $response = [
            'success' => true,
            'comment' => $comment,
        ];

        return response()->json($response);
    }
}
