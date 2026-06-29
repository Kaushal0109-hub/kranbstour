<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    private const RESERVED_SLUGS = ['blog', 'contact', 'login', 'register', 'admin', 'dashboard', 'search', 'tours'];

    public function index(): View
    {
        return view('admin.master.cms-pages.index', [
            'pages' => CmsPage::query()->orderBy('sort_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.master.cms-pages.form', ['page' => new CmsPage()]);
    }

    public function store(Request $request): RedirectResponse
    {
        CmsPage::create($this->validated($request));

        return redirect()->route('admin.master.cms-pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(CmsPage $cmsPage): View
    {
        return view('admin.master.cms-pages.form', ['page' => $cmsPage]);
    }

    public function update(Request $request, CmsPage $cmsPage): RedirectResponse
    {
        $cmsPage->update($this->validated($request, $cmsPage));

        return redirect()->route('admin.master.cms-pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(CmsPage $cmsPage): RedirectResponse
    {
        $cmsPage->delete();

        return back()->with('success', 'Page deleted.');
    }

    private function validated(Request $request, ?CmsPage $page = null): array
    {
        $data = $request->validate([
            'slug' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('cms_pages', 'slug')->ignore($page?->id),
                Rule::notIn(self::RESERVED_SLUGS),
            ],
            'title' => ['required', 'string', 'max:150'],
            'heading' => ['required', 'string', 'max:200'],
            'content' => ['nullable', 'string'],
            'show_in_footer' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['show_in_footer'] = $request->boolean('show_in_footer');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
