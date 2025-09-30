<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * Handle image upload for CKEditor.
     * Validates the image, saves it with a unique name, and returns the URL in JSON format.
     * Response format is compatible with TinyMCE.
     */
    public function uploadCKEditorImage(Request $request)
    {
        try {
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');

                // Tạo tên file unique với timestamp
                $timestamp = Carbon::now()->format('YmdHis');
                $randomString = Str::random(8);
                $extension = $file->getClientOriginalExtension();
                $filename = "post_content_{$timestamp}_{$randomString}.{$extension}";

                // Tạo thư mục nếu chưa có
                $uploadPath = public_path('images/posts');
                if (! file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Di chuyển file
                $file->move($uploadPath, $filename);

                $url = asset("images/posts/{$filename}");

                // Response format cho TinyMCE
                return response()->json([
                    'url' => $url,
                ]);
            }

            return response()->json([
                'error' => __('admin.no_file_uploaded'),
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => __('admin.upload_failed').': '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle featured image upload for posts.
     * Validates the image, saves it with a unique name, and returns the URL and filename in JSON format.
     */
    public function uploadPostImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Tạo tên file unique với timestamp
            $timestamp = Carbon::now()->format('YmdHis');
            $randomString = Str::random(8);
            $extension = $file->getClientOriginalExtension();
            $filename = "post_featured_{$timestamp}_{$randomString}.{$extension}";

            // Tạo thư mục nếu chưa có
            $uploadPath = public_path('images/posts');
            if (! file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Di chuyển file
            $file->move($uploadPath, $filename);

            $url = asset("images/posts/{$filename}");

            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => $filename,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('admin.upload_failed'),
        ], 400);
    }

    /**
     * Handle image upload for TinyMCE editor.
     * Validates the image, saves it with a unique name, and returns the location URL in JSON format.
     * Response format includes 'location' key for TinyMCE compatibility.
     */
    public function uploadTinyMCEImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Tạo tên file unique với timestamp
                $timestamp = Carbon::now()->format('YmdHis');
                $randomString = Str::random(8);
                $extension = $file->getClientOriginalExtension();
                $filename = "tinymce_content_{$timestamp}_{$randomString}.{$extension}";

                // Tạo thư mục nếu chưa có
                $uploadPath = public_path('images/posts');
                if (! file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Di chuyển file
                $file->move($uploadPath, $filename);

                $url = asset("images/posts/{$filename}");

                // Response format cho TinyMCE (cần 'location' key)
                return response()->json([
                    'location' => $url,
                ]);
            }

            return response()->json([
                'error' => 'No file uploaded',
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Upload failed: '.$e->getMessage(),
            ], 500);
        }
    }

    // public function uploadImg(Request $request){
    //     switc (this === Post)
    //     return $this->uploadTinyMCEImage($request);
    // }
}
