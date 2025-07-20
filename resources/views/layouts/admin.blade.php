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

    <!-- Load CKEditor5 Full-featured từ CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionElement = document.querySelector('.description');
            if (descriptionElement) {
                // Tăng chiều cao textarea ban đầu
                descriptionElement.style.minHeight = '400px';
                
                ClassicEditor
                .create(descriptionElement, {
                  toolbar: {
                    items: [
                      'heading', '|',
                      'fontSize', 'fontFamily', '|',
                      'bold', 'italic', 'underline', 'strikethrough', '|',
                      'fontColor', 'fontBackgroundColor', '|',
                      'alignment', 'outdent', 'indent', '|',
                      'bulletedList', 'numberedList', 'blockQuote', '|',
                      'link', 'insertTable', 'imageUpload', '|',
                      'undo', 'redo', 'removeFormat'
                    ],
                    shouldNotGroupWhenFull: true
                  },
                  heading: {
                    options: [
                      { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                      { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                      { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                      { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                      { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                    ]
                  },
                  fontSize: {
                    options: [ 
                      'tiny', 'small', 'default', 'big', 'huge',
                      8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 24, 26, 28, 30, 32, 34, 36
                    ],
                    supportAllValues: true
                  },
                  fontFamily: {
                    options: [
                      'default',
                      'Arial, Helvetica, sans-serif',
                      'Courier New, Courier, monospace', 
                      'Georgia, serif',
                      'Times New Roman, Times, serif',
                      'Trebuchet MS, Helvetica, sans-serif',
                      'Verdana, Geneva, sans-serif',
                      'Comic Sans MS, cursive',
                      'Impact, sans-serif'
                    ],
                    supportAllValues: true
                  },
                  image: {
                    toolbar: [
                      'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ],
                    upload: {
                      types: ['jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff']
                    }
                  },
                  // Cấu hình upload ảnh cho CKEditor
                  ckfinder: {
                    uploadUrl: '{{ route("admin.upload.ckeditor.image") }}?_token={{ csrf_token() }}'
                  },
                  table: {
                    contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                  },
                  // Tăng chiều cao editor
                  editorConfig: {
                    height: '400px'
                  }
                }).then(editor => {
                  window.editorInstance = editor;
                  // Tăng chiều cao vùng chỉnh sửa
                  const editableElement = editor.ui.getEditableElement();
                  if (editableElement) {
                    editableElement.style.minHeight = '350px';
                  }
                })
                .catch(error => {
                  console.error('CKEditor initialization error:', error);
                });
            }

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