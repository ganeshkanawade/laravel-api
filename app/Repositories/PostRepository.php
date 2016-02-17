<?php
namespace App\Repositories;

use App\User;
use App\Models\Post;

class PostRepository
{
    /**
    * Get all of the tasks for a given user.
    *
    * @param  User  $user
    * @return Collection
    */
    public function userPosts(User $user)
    {
        return Post::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get();
    }
}