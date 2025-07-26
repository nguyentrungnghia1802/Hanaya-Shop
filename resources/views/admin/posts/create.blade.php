@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm bài viết mới</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        @if(isset($edit) && $edit && isset($post))
            <form method="POST" action="{{ route('admin.post.update', ['id' => $post->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Tiêu đề</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full px-4 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nội dung</label>
                    <textarea name="content" id="myeditorinstance" class="w-full px-4 py-2 border rounded">{{ old('content', $post->content) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Ảnh</label>
                    <input type="file" name="image" id="imageInput" class="w-full px-4 py-2 border rounded">
                    @if($post->image)
                        <img id="previewImage" src="{{ asset('images/posts/' . $post->image) }}" alt="Ảnh hiện tại" class="h-32 mt-2 rounded">
                    @else
                        <img id="previewImage" src="#" alt="Preview" class="h-32 mt-2 rounded" style="display:none;">
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2 border rounded">
                        <option value="1" {{ $post->status ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ !$post->status ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Cập nhật</button>
                    <button type="button" data-confirm-cancel data-redirect-url="{{ route('admin.post.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Huỷ</button>
                </div>
            </form>
        @else
            <form method="POST" action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Tiêu đề</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nội dung</label>
                    <textarea name="content" id="myeditorinstance" class="w-full px-4 py-2 border rounded">{{ old('content') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Ảnh</label>
                    <input type="file" name="image" id="imageInput" class="w-full px-4 py-2 border rounded">
                    <img id="previewImage" src="#" alt="Preview" class="h-32 mt-2 rounded" style="display:none;">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2 border rounded">
                        <option value="1" selected>Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Tạo mới</button>
                    <button type="button" data-confirm-cancel data-redirect-url="{{ route('admin.post.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Huỷ</button>
                </div>
            </form>
        @endif
    </div>
</div>

@push('scripts')
<!-- CSRF token meta tag cho TinyMCE upload -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Hướng dẫn sử dụng TinyMCE upload -->
<div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 10px 0; color: #1565c0;">📋 Hướng dẫn sử dụng:</h3>
    <ul style="margin: 0; color: #1565c0;">
        <li><strong>Nút Image:</strong> Nhấn vào nút Image trên toolbar</li>
        <li><strong>Drag & Drop:</strong> Kéo file hình ảnh vào editor</li>
        <li><strong>Copy/Paste:</strong> Paste hình ảnh từ clipboard</li>
        <li><strong>Giới hạn:</strong> File tối đa 10MB, định dạng: jpg, png, gif, webp</li>
    </ul>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: 'textarea#myeditorinstance',
    height: 400,
    plugins: 'code table lists image link media paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | code fullscreen',
    images_upload_handler: function (blobInfo, success, failure, progress) {
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }
        fetch("{{ route('admin.upload.tinymce.image') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.location) {
                success(result.location);
            } else {
                failure(result.error || 'Upload failed');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            failure('Network error occurred');
        });
    },
    paste_data_images: true,
    images_upload_credentials: true,
    images_file_types: 'jpg,svg,webp,png,gif',
    automatic_uploads: true,
    file_picker_types: 'image',
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype === 'image') {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 10 * 1024 * 1024) {
                        alert('File quá lớn! Vui lòng chọn file dưới 10MB.');
                        return;
                    }
                    const formData = new FormData();
                    formData.append('file', file);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        formData.append('_token', csrfToken.getAttribute('content'));
                    }
                    fetch("{{ route('admin.upload.tinymce.image') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.location) {
                            callback(result.location, {
                                alt: file.name,
                                title: file.name
                            });
                        } else {
                            alert('Upload failed: ' + (result.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        alert('Network error occurred');
                    });
                }
            };
            input.click();
        }
    },
    setup: function(editor) {
        editor.on('init', function() {
            this.getDoc().body.style.fontFamily = 'Arial, sans-serif';
            this.getDoc().body.style.fontSize = '14px';
        });
        editor.on('dragover', function(e) {
            e.preventDefault();
        });
        editor.on('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    e.preventDefault();
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File quá lớn! Vui lòng chọn file dưới 5MB.');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function() {
                        const img = `<img src="${reader.result}" alt="${file.name}" style="max-width: 100%; height: auto;">`;
                        editor.insertContent(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    },
    contextmenu: 'link image table',
    mobile: {
        theme: 'mobile',
        plugins: ['autosave', 'lists', 'autolink'],
        toolbar: ['undo', 'bold', 'italic', 'styleselect']
    }
});

function getContent() {
    const content = tinymce.get('myeditorinstance').getContent();
    alert(content);
}
function setContent() {
    const sampleContent = `
        <h2>Nội dung mẫu</h2>
        <p>Đây là một đoạn văn bản mẫu với <strong>text đậm</strong> và <em>text nghiêng</em>.</p>
        <ul>
            <li>Mục danh sách 1</li>
            <li>Mục danh sách 2</li>
            <li>Mục danh sách 3</li>
        </ul>
        <p>Bạn có thể chèn hình ảnh bằng cách:</p>
        <ol>
            <li>Nhấn nút Image trên toolbar</li>
            <li>Kéo thả file hình ảnh vào editor</li>
            <li>Copy/paste hình ảnh từ clipboard</li>
        </ol>
    `;
    tinymce.get('myeditorinstance').setContent(sampleContent);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-confirm-cancel]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (confirm('Bạn có chắc chắn muốn huỷ? Mọi thay đổi sẽ không được lưu.')) {
                window.location.href = btn.getAttribute('data-redirect-url');
            }
        });
    });
});
</script>
@endpush
@endsection
