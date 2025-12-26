<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|max:255',
            'icerik' => 'required',
            'resim'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('resim');
        $data['slug'] = Str::slug($request->baslik) . '-' . uniqid(); // Benzersiz slug
        $data['yazar'] = auth()->user()->name ?? 'Admin';

        if ($request->hasFile('resim')) {
            $file = $request->file('resim');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('blogs', $filename, 'public');
            $data['resim'] = 'blogs/' . $filename;
        }

        Blog::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı başarıyla eklendi.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'baslik' => 'required|max:255',
            'icerik' => 'required',
            'resim'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $blog = Blog::findOrFail($id);
        $data = $request->except('resim');
        $data['slug'] = Str::slug($request->baslik); // Başlık değişirse slug da değişsin

        if ($request->hasFile('resim')) {
            // Eski resmi sil
            if ($blog->resim && Storage::disk('public')->exists($blog->resim)) {
                Storage::disk('public')->delete($blog->resim);
            }

            $file = $request->file('resim');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('blogs', $filename, 'public');
            $data['resim'] = 'blogs/' . $filename;
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı güncellendi.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->resim && Storage::disk('public')->exists($blog->resim)) {
            Storage::disk('public')->delete($blog->resim);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı silindi.');
    }


}