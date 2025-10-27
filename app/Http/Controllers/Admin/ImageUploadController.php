<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImageUploadController extends Controller
{
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
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Di chuyển file
                $file->move($uploadPath, $filename);
                
                $url = asset("images/posts/{$filename}");
                
                // Response format cho TinyMCE
                return response()->json([
                    'url' => $url
                ]);
            }
            
            return response()->json([
                'error' => 'No file uploaded'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
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
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Di chuyển file
            $file->move($uploadPath, $filename);
            
            $url = asset("images/posts/{$filename}");
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => $filename
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Upload failed'
        ], 400);
    }
    
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
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Di chuyển file
                $file->move($uploadPath, $filename);
                
                $url = asset("images/posts/{$filename}");
                
                // Response format cho TinyMCE (cần 'location' key)
                return response()->json([
                    'location' => $url
                ]);
            }
            
            return response()->json([
                'error' => 'No file uploaded'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function uploadImg(Request $request){
    //     switc (this === Post)
    //     return $this->uploadTinyMCEImage($request);
    // }
}
