<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.master.blog-posts.index', [
            'posts' => BlogPost::query()->orderByDesc('published_at')->orderBy('sort_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.master.blog-posts.form', ['post' => new BlogPost()]);
    }

    public function store(Request $request): RedirectResponse
    {
        BlogPost::create($this->validated($request));

        return redirect()->route('admin.master.blog-posts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.master.blog-posts.form', ['post' => $blogPost]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $blogPost->update($this->validated($request, $blogPost));

        return redirect()->route('admin.master.blog-posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();

        return back()->with('success', 'Blog post deleted.');
    }

    private function validated(Request $request, ?BlogPost $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', 'unique:blog_posts,slug,'.($post?->id ?? 'NULL')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['author_name'] = $data['author_name'] ?: config('site.name');

        return $this->mergeUploadedImages($request, $data, ['featured_image'], 'blog', $post);
    }
}
