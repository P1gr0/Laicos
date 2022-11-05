<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Grant authorization to edit post/comment only to the user that created.
        Gate::define('update_edit-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });
        Gate::define('update_edit-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id;
        });
    }
}
