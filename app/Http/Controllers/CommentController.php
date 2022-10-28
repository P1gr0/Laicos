<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif,mpeg,mp4|image|max:30000'
        ]);

        $file_name = NULL;

        if ($request->hasFile('image')) {
            $file_name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images'), $file_name);
        }

        $comment = Comment::create([
            'content' => htmlspecialchars($request->content),
            'user_id' => $request->user()->id,
            'image' => $file_name,
            'post_id' => $request->post_id
        ]);

        $comment->is_author = $comment->isAuthor();
        $comment->user = $request->user();
        $comment->user_image = empty($request->user()->image) ? 'default.png' : $request->user()->image;
        
        return $comment;
    }

    public function toggleLike(Comment $comment)
    {
        if ($comment->liked(Auth::user()->id)) $comment->unlike();
        else $comment->like();
        $comment->save();
    }

    public function getLikes(Comment $comment)
    {
        return [$comment->likeCount, $comment->liked(Auth::user()->id)];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if(File::exists(public_path('images/' . $comment->image))){
            File::delete(public_path('images/' . $comment->image));
        }
        $comment->delete();
    }
}
