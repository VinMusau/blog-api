<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Policies\PostPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Register the Post model policy
        Post::class => PostPolicy::class,
    ];
}
