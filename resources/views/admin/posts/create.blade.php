@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('admin.create_new_post') }}</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        @if(isset($edit) && $edit && isset($post))
            <form method="POST" action="{{ route('admin.post.update', ['id' => $post->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.title') }}</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full px-4 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.content') }}</label>
                    <textarea name="content" id="myeditorinstance" class="w-full px-4 py-2 border rounded">{{ old('content', $post->content) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.Image') }}</label>
                    <input type="file" name="image" id="imageInput" class="w-full px-4 py-2 border rounded">
                    @if($post->image)
                        <img id="previewImage" src="{{ asset('images/posts/' . $post->image) }}" alt="Current Image" class="h-32 mt-2 rounded">
                    @else
                        <img id="previewImage" src="#" alt="Preview" class="h-32 mt-2 rounded" style="display:none;">
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.status') }}</label>
                    <select name="status" class="w-full px-4 py-2 border rounded">
                        <option value="1" {{ $post->status ? 'selected' : '' }}>{{ __('admin.visible') }}</option>
                        <option value="0" {{ !$post->status ? 'selected' : '' }}>{{ __('admin.hidden') }}</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">{{ __('admin.update') }}</button>
                    <button type="button" data-confirm-cancel data-redirect-url="{{ route('admin.post.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">{{ __('admin.cancel') }}</button>
                </div>
            </form>
        @else
            <form method="POST" action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.content') }}</label>
                    <textarea name="content" id="myeditorinstance" class="w-full px-4 py-2 border rounded">{{ old('content') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.Image') }}</label>
                    <input type="file" name="image" id="imageInput" class="w-full px-4 py-2 border rounded">
                    <img id="previewImage" src="#" alt="Preview" class="h-32 mt-2 rounded" style="display:none;">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('admin.status') }}</label>
                    <select name="status" class="w-full px-4 py-2 border rounded">
                        <option value="1" selected>{{ __('admin.visible') }}</option>
                        <option value="0">{{ __('admin.hidden') }}</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">{{ __('admin.create') }}</button>
                    <button type="button" data-confirm-cancel data-redirect-url="{{ route('admin.post.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        @endif
    </div>
</div>

@push('scripts')
<!-- CSRF token meta tag cho TinyMCE upload -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Guide TinyMCE upload -->
<div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 10px 0; color: #1565c0;">📋 {{ __('admin.guide') }}:</h3>
    <ul style="margin: 0; color: #1565c0;">
       <li><strong>{{ __('admin.image_button_title') }}</strong> {{ __('admin.image_button_desc') }}</li>
       <li><strong>{{ __('admin.drag_drop_title') }}</strong> {{ __('admin.drag_drop_desc') }}</li>
       <li><strong>{{ __('admin.copy_paste_title') }}</strong> {{ __('admin.copy_paste_desc') }}</li>
       <li><strong>{{ __('admin.limitations_title') }}</strong> {{ __('admin.limitations_desc') }}</li>
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
    contextmenu: 'link image table'

});

function getContent() {
    const content = tinymce.get('myeditorinstance').getContent();
    alert(content);
}
function setContent() {
    const sampleContent = `
        <h2>Sample Content</h2>
        <p>This is a sample paragraph with <strong>bold text</strong> and <em>italic text</em>.</p>
        <ul>
            <li>List item 1</li>
            <li>List item 2</li>
            <li>List item 3</li>
        </ul>
        <p>You can insert images by:</p>
        <ol>
            <li>Clicking the Image button on the toolbar</li>
            <li>Dragging and dropping image files into the editor</li>
            <li>Copying and pasting images from the clipboard</li>
        </ol>
    `;
    tinymce.get('myeditorinstance').setContent(sampleContent);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-confirm-cancel]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (confirm('Do you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = btn.getAttribute('data-redirect-url');
            }
        });
    });
});
</script>
@endpush
@endsection
