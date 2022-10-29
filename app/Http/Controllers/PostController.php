<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


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
 /*     return Auth::user()->posts->sortByDesc('created_at')->values(); */
        //return Post::orderBy('created_at', 'desc')->paginate(10); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif,mpeg,mp4|max:20000'
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

       return $post; 
    }

    public function show(Post $post)
    {
        $post->user_image = empty($post->user->image) ? 'default.png' : $post->user->image;
        return view('posts.show', ['post'=>$post]);
    }

    public function getComments(Post $post){
        $comments = $post->comments->sortByDesc('created_at')->values();;
        foreach($comments as $comment){
            $comment->user = $comment->user;
            $comment->user_image = empty($comment->user->image) ? 'default.png' : $comment->user->image;
            $comment->is_author = Auth::user() == $comment->user;
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

    public function getLikes(Post $post)
    {
        return [$post->likeCount, $post->liked(Auth::user()->id)]; 
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(File::exists(public_path('images/' . $post->image))){
            File::delete(public_path('images/' . $post->image));
        }
        $post->delete();
    }
}
