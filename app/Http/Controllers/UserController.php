<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
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
        /* return view('admin')->with('users', User::all()->except(Auth::id())->makeVisible('email')); */
        return User::all()->except(Auth::id())->makeVisible('email');
    }

    public function search($name)
    {
        return view('users.search')->with('users', User::where('name', 'like', "%$name%")->get());
    }

    public function getCounts(User $user)
    {
        return [$user->posts->count(), $user->comments->count()];
    }

    public function setImage(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:png,jpg,jpeg|max:10000'
        ]);

        $file_name = NULL;
        if ($request->image) {
            $file_name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images/profile'), $file_name);
        }

        if ($request->user()->image)
            File::delete(public_path('images/profile/' . $request->user()->image));

        $request->user()->update([
            "image" => $file_name
        ]);
    }

    public function deleteImage(Request $request, User $user)
    {
        if ($request->user()->image) {
            File::delete(public_path('images/profile/' . $request->user()->image));
            $user->update([
                "image" => null
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user == Auth::user()) return view('home')->with('user', $user);
        return view('users.show', ['user' => $user]);
    }

    public function getPosts(User $user)
    {
        $posts = $user->posts()->with('user')->orderBy('created_at', 'desc')->paginate(10);
        foreach ($posts as $post) {
            $post->likes = $post->likeCount;
            $post->liked = $post->liked($user->id);
        }
        return $posts;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image
        ]);

        return redirect('/admin')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin')->with('success', 'User removed successfully!');
    }
}
