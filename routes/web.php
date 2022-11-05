<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/posts', PostController::class)->except('create');
Route::post('posts/togglelike/{post}', [PostController::class, 'toggleLike']);
Route::get('posts/getlikes/{post}', [PostController::class, 'getLikes']);
Route::get('posts/{post}/comments', [PostController::class, 'getComments']);

Route::resource('/comments', CommentController::class)->except(['index', 'create']);
Route::post('comments/togglelike/{comment}', [CommentController::class, 'toggleLike']);
Route::get('comments/getlikes/{comment}', [CommentController::class, 'getLikes']);

Route::resource('/users', UserController::class);
Route::post('setimage', [UserController::class, 'setImage']);
Route::put('rmvimage/{user}', [UserController::class, 'deleteImage']);
Route::get('getcounts/{user}', [UserController::class, 'getCounts']);
Route::get('getposts/{user}', [UserController::class, 'getPosts']);
Route::get('search/{name}', [UserController::class, 'search']);

Route::resource('/friendship', FriendController::class)->except(['edit', 'create']);
Route::get('getstatus/{id}', [FriendController::class, 'getStatus']);

Route::get('/chat', [ChatsController::class, 'index']);
Route::get('/messages/{receiver}', [ChatsController::class, 'fetchMessages']);
Route::post('/messages', [ChatsController::class, 'sendMessage']);
