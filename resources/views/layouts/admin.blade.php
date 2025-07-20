<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSS cho CKEditor & TinyMCE content -->
    <style>
        .ck-editor__editable_inline {
            min-height: 350px;
        }
        .ck.ck-toolbar {
            border-top: 1px solid #c4c4c4;
            border-left: 1px solid #c4c4c4;
            border-right: 1px solid #c4c4c4;
        }
        .ck.ck-editor__main > .ck-editor__editable {
            border-left: 1px solid #c4c4c4;
            border-right: 1px solid #c4c4c4;
            border-bottom: 1px solid #c4c4c4;
        }

        /* TinyMCE content style fix - Bỏ CSS làm mất format */
        .prose {
            color: #374151;
            max-width: none;
        }
        .prose h1 {
            font-size: 2.25em;
            font-weight: 800;
            line-height: 1.1111111;
            margin-top: 0;
            margin-bottom: 0.8888889em;
        }
        .prose h2 {
            font-size: 1.875em;
            font-weight: 700;
            line-height: 1.3333333;
            margin-top: 2em;
            margin-bottom: 1em;
        }
        .prose h3 {
            font-size: 1.5em;
            font-weight: 600;
            line-height: 1.6;
            margin-top: 1.6em;
            margin-bottom: 0.6em;
        }
        .prose strong, .prose b {
            font-weight: 600;
        }
        .prose em, .prose i {
            font-style: italic;
        }
        .prose ul, .prose ol {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
            padding-left: 1.625em;
        }
        .prose li {
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }
        .prose blockquote {
            font-weight: 500;
            font-style: italic;
            color: #374151;
            border-left-width: 0.25rem;
            border-left-color: #d1d5db;
            quotes: "\201C""\201D""\2018""\2019";
            margin-top: 1.6em;
            margin-bottom: 1.6em;
            padding-left: 1em;
        }
        .prose img {
            margin-top: 2em;
            margin-bottom: 2em;
            max-width: 100%;
            height: auto;
        }
        .prose p {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
        }
        
        /* TinyMCE image upload styling */
        .tox .tox-dialog__body-content {
            padding: 1rem;
        }
        .tox .tox-dialog__title {
            color: #374151;
            font-weight: 600;
        }
        .tox-dialog .tox-textfield {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- Loading overlay đặt ngay sau <body> -->
    <div id="pageLoadingOverlay" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);align-items:center;justify-content:center;">
        <div style="font-size:2rem;color:#2563eb;">
            <svg class="animate-spin h-10 w-10 mr-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            Loading...
        </div>
    </div>
    <div class="min-h-screen">
        @include('layouts.admin-nav')
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Load TinyMCE từ CDN - Editor đầy đủ tính năng -->
    <script src="https://cdn.tiny.cloud/1/y1zo0i12q8i692ria3ibrw4baa79o7h6yaa1tgqpy03fwz1x/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: 'textarea.description',
                height: 400,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                         'fontsize forecolor backcolor | bold italic underline strikethrough | ' +
                         'alignleft aligncenter alignright alignjustify | ' +
                         'bullist numlist outdent indent | ' +
                         'image link | removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                font_size_formats: '8px 9px 10px 11px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px',
                color_map: [
                    "000000", "Black",
                    "993300", "Burnt orange",
                    "333300", "Dark olive",
                    "003300", "Dark green",
                    "003366", "Dark azure",
                    "000080", "Navy Blue",
                    "333399", "Indigo",
                    "333333", "Very dark gray",
                    "800000", "Maroon",
                    "FF6600", "Orange",
                    "808000", "Olive",
                    "008000", "Green",
                    "008080", "Teal",
                    "0000FF", "Blue",
                    "666699", "Grayish blue",
                    "808080", "Gray",
                    "FF0000", "Red",
                    "FF9900", "Amber",
                    "99CC00", "Yellow green",
                    "339966", "Sea green",
                    "33CCCC", "Turquoise",
                    "3366FF", "Royal blue",
                    "800080", "Purple",
                    "999999", "Medium gray",
                    "FF00FF", "Magenta",
                    "FFCC00", "Gold",
                    "FFFF00", "Yellow",
                    "00FF00", "Lime",
                    "00FFFF", "Aqua",
                    "00CCFF", "Sky blue",
                    "993366", "Red violet",
                    "FFFFFF", "White",
                    "FF99CC", "Pink",
                    "FFCC99", "Peach",
                    "FFFF99", "Light yellow",
                    "CCFFCC", "Pale green",
                    "CCFFFF", "Pale cyan",
                    "99CCFF", "Light sky blue",
                    "CC99FF", "Plum"
                ],
                block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;',
                
                // Cấu hình upload ảnh
                images_upload_url: '{{ route("admin.upload.ckeditor.image") }}',
                images_upload_handler: function (blobInfo, success, failure, progress) {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route("admin.upload.ckeditor.image") }}');
                    
                    xhr.upload.onprogress = function (e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        if (xhr.status === 403) {
                            failure('HTTP Error: ' + xhr.status, { remove: true });
                            return;
                        }

                        if (xhr.status < 200 || xhr.status >= 300) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }

                        let json;
                        try {
                            json = JSON.parse(xhr.responseText);
                        } catch (e) {
                            failure('Invalid response: ' + xhr.responseText);
                            return;
                        }

                        if (!json || !json.url) {
                            failure(json.error || 'Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        success(json.url);
                    };

                    xhr.onerror = function () {
                        failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                    };

                    const formData = new FormData();
                    formData.append('upload', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', '{{ csrf_token() }}');

                    xhr.send(formData);
                },
                
                // Cấu hình ảnh
                image_title: true,
                image_description: false,
                image_dimensions: false,
                image_advtab: true,
                
                // Tự động resize ảnh
                images_upload_credentials: true,
                automatic_uploads: true,
                
                // Paste ảnh từ clipboard
                paste_data_images: true,
                
                // File picker callback (fallback nếu upload handler fail)
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.onchange = function() {
                            const file = this.files[0];
                            const formData = new FormData();
                            formData.append('upload', file);
                            formData.append('_token', '{{ csrf_token() }}');

                            fetch('{{ route("admin.upload.ckeditor.image") }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.url) {
                                    callback(data.url, { title: file.name });
                                } else {
                                    console.error('Upload failed:', data);
                                }
                            })
                            .catch(error => {
                                console.error('Upload error:', error);
                            });
                        };
                        input.click();
                    }
                },
                
                setup: function (editor) {
                    editor.on('init', function () {
                        console.log('TinyMCE đã khởi tạo thành công với upload ảnh!');
                    });
                }
            });

            // xử lí khi chọn ảnh
            const imageInput = document.getElementById('imageInput');
            if (imageInput) {
                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewImage = document.getElementById('previewImage');
                            if (previewImage) {
                                previewImage.src = e.target.result;
                                previewImage.style.display = 'block';
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chặn click trên nav-link và delay chuyển trang
        document.querySelectorAll('nav a, .admin-nav a, .sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Chỉ xử lý link nội bộ (không có target _blank, không phải anchor, không có modifier key)
                if (
                    this.target === '_blank' ||
                    this.href.startsWith('javascript:') ||
                    this.href === '#' ||
                    e.ctrlKey || e.shiftKey || e.metaKey || e.altKey
                ) return;
                e.preventDefault();
                document.getElementById('pageLoadingOverlay').style.display = 'flex';
                setTimeout(() => {
                    window.location.href = this.href;
                }, 150);
            });
        });
    });
    </script>
</body>
</html>