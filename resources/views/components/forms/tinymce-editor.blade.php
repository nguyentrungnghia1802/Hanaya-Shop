{{-- TinyMCE Editor Component - CSP Compliant --}}
@props([
    'name' => 'content',
    'id' => null,
    'value' => '',
    'placeholder' => 'Enter content...',
    'height' => 300,
    'required' => false,
    'uploadUrl' => null
])

@php
    $elementId = $id ?? 'tinymce-' . $name;
    $uploadUrl = $uploadUrl ?? route('admin.upload.tinymce.image', [], false);
@endphp

<div class="tinymce-wrapper">
    <textarea
        name="{{ $name }}"
        id="{{ $elementId }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="form-control"
    >{{ old($name, $value) }}</textarea>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/y1zo0i12q8i692ria3ibrw4baa79o7h6yaa1tgqpy03fwz1x/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#{{ $elementId }}',
            height: {{ $height }},
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'fullscreen', 'insertdatetime', 'media', 'table', 
                'help', 'wordcount', 'emoticons', 'codesample', 'code', 'visualchars', 'quickbars'
            ],
            toolbar: [
                'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor',
                'alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist',
                'image media link table | removeformat | code fullscreen help'
            ],
            menubar: false,
            branding: false,
            images_upload_url: '{{ $uploadUrl }}',
            images_upload_credentials: true,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            file_picker_types: 'image',
            image_title: true,
            automatic_uploads: true,
            images_reuse_filename: true,
            content_style: 'body { font-family: Figtree, Arial, sans-serif; font-size: 14px; line-height: 1.6; margin: 1rem; }',
            // Mobile configuration
            mobile: {
                theme: 'silver',
                plugins: 'image link lists code bold italic',
                toolbar: 'undo redo | bold italic | image link | bullist numlist',
                menubar: false
            },
            min_height: 150,
            max_height: 500,
            autoresize_bottom_margin: 16,
            resize: 'both',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
                
                // Make editor responsive
                window.addEventListener('resize', function() {
                    if (window.innerWidth < 768) {
                        editor.settings.toolbar = 'undo redo | bold italic | image link';
                    } else {
                        editor.settings.toolbar = [
                            'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor',
                            'alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist',
                            'image media link table | removeformat | code fullscreen help'
                        ];
                    }
                });
            },
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', '{{ $uploadUrl }}');
                
                var token = document.querySelector('meta[name="csrf-token"]');
                if (token) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                }
                
                xhr.onload = function() {
                    if (xhr.status === 403) {
                        failure('HTTP Error: ' + xhr.status, { remove: true });
                        return;
                    }
                    
                    if (xhr.status < 200 || xhr.status >= 300) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    
                    var json = JSON.parse(xhr.responseText);
                    
                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    
                    success(json.location);
                };
                
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }
        });
    }
});
</script>
@endpush
