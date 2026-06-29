<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('blog.index', [
            'posts' => BlogPost::query()->published()->paginate(9),
        ]);
    }

    public function show(BlogPost $post): View
    {
        if (! $post->is_active || ($post->published_at && $post->published_at->isFuture())) {
            abort(404);
        }

        $related = BlogPost::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
