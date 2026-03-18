<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;
    public function update(User $user, Post $post): Response|bool
    {
        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('You do not own this post.');
    }

    public function delete(User $user, Post $post): Response|bool
    {
        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('You do not own this post.');
    }

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true; // Grant all permissions to admin users
        }

        return null; // Return null to continue with normal authorization checks for non-admin users
    }
}
