<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;

use App\Models\Friend;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Mockery\Undefined;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        foreach (Auth::user()->friends as $friend)
            if (empty($posts)) $posts = $friend->posts();
            else $posts->union($friend->posts());

        $posts = $posts->orderBy('created_at', 'desc')->paginate(10);

        foreach ($posts as $post) {
            /*  $post->is_author = false;  */
            $post->likes = $post->likeCount;
            $post->liked = $post->liked(Auth::user());
        }

        return $posts;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif,mpeg|max:20000'
        ]);

        $file_name = NULL;

        if ($request->hasFile('image')) {
            $file_name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images'), $file_name);
        }

        $post = Post::create([
            'title' => htmlspecialchars($request->title),
            'content' => htmlspecialchars($request->content),
            'user_id' => $request->user()->id,
            'image' => $file_name
        ]);

        $post->likes = 0;

        return $post;
    }

    public function show(Post $post)
    {
        $post->likes = $post->likeCount;
        $post->liked = $post->liked(Auth::user()->id);
        return view('posts.show', ['post' => $post]);
    }

    public function getComments(Post $post)
    {
        $comments = $post->comments()->with('user')->orderBy('created_at', 'desc')->paginate(15);
        foreach ($comments as $comment) {
            $comment->is_author = Auth::user() == $comment->user;
            $comment->likes = $comment->likeCount;
            $comment->liked = $comment->liked(Auth::user()->id);
        }
        return $comments;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    public function toggleLike(Post $post)
    {
        if ($post->liked(Auth::user()->id)) $post->unlike();
        else $post->like();
        $post->save();
    }

    //return users who liked a post
    public function getLikes(Post $post)
    {
        foreach ($post->likes as $like)
            $users[] = User::find($like->user_id);
        return empty($users) ? [] : $users;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->image)
            File::delete(public_path('images/' . $post->image));
        $post->delete();
    }
}
