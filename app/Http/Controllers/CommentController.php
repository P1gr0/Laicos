<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            'image' => 'mimes:jpeg,jpg,png,gif,bmp,svg|image|max:20000'
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

        $comment->is_author = true;
        $comment->user = $request->user();
        $comment->likes = 0;

        return $comment;
    }

    public function toggleLike(Comment $comment)
    {
        if ($comment->liked(Auth::user()->id)) $comment->unlike();
        else $comment->like();
        $comment->save();
    }

    //return users who liked a post
    public function getLikes(Comment $comment)
    {
        foreach ($comment->likes as $like)
            $users[] = User::find($like->user_id);
        return empty($users) ? [] : $users;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        if (!Gate::allows('update_remove-comment', $comment))
            abort(404);

        return view("comments.edit")->with('comment', $comment);
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
        if (!Gate::allows('update_remove-comment', $comment))
            abort(403);

        $request->validate([
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif,bmp,svg|image|max:20000'
        ]);

        if ($request->updateImage) {
            $file_name = NULL;
            if ($comment->image)
                File::delete(public_path('images/' . $comment->image));
            if ($request->hasFile('image')) {
                $file_name = time() . '-' . $request->file('image')->getClientOriginalName();
                $request->image->move(public_path('images'), $file_name);
            }
            $comment->update(['image' => $file_name]);
        }

        $comment->update(['content' => htmlspecialchars($request->content)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if (!Gate::allows('update_remove-comment', $comment))
            abort(403);
        if ($comment->image)
            File::delete(public_path('images/' . $comment->image));
        $comment->delete();
    }
}
