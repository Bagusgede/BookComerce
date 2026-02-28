<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    /**
     * Apply auth + admin middleware to controller routes
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Handle image upload from CKEditor (SimpleUploadAdapter)
     * Expects file field name 'upload' and returns JSON { url: '...' }
     */
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);

        $file = $request->file('upload');
        $path = $file->store('uploads/ckeditor', 'public');

        // Return JSON structure expected by CKEditor SimpleUploadAdapter
        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
