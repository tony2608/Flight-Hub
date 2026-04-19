<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingContent;
use Illuminate\Support\Facades\Storage;

class AdminCmsController extends Controller
{
    public function index()
    {
        $contents = LandingContent::all()->keyBy('section');
        return view('admin.cms.index', compact('contents'));
    }

    public function update(Request $request)
    {
        if(!$request->has('content')) {
            return back()->with('error', 'Tidak ada data yang diubah.');
        }

        foreach ($request->content as $section => $data) {
            $content = LandingContent::firstOrCreate(['section' => $section]);
            
            $content->title = $data['title'] ?? $content->title;
            $content->description = $data['description'] ?? $content->description;
            $content->button_text = $data['button_text'] ?? $content->button_text;

            // Handle Upload Foto
            if (isset($data['image']) && $request->hasFile("content.$section.image")) {
                if ($content->image) { 
                    Storage::disk('public')->delete($content->image); 
                }
                $path = $request->file("content.$section.image")->store('landing', 'public');
                $content->image = $path;
            }

            $content->save();
        }

        return back()->with('success', 'Halaman Utama berhasil diperbarui! Cek beranda sekarang.');
    }
}