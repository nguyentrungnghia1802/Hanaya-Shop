{{-- TinyMCE Configuration Component - CSP Compliant --}}
{{-- Để TinyMCE đầy đủ tính năng, hãy đăng ký API key miễn phí tại https://www.tiny.cloud/ và thay YOUR_API_KEY bên dưới --}}
<script src="https://cdn.tiny.cloud/1/y1zo0i12q8i692ria3ibrw4baa79o7h6yaa1tgqpy03fwz1x/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
  tinymce.init({
    selector: 'textarea#myeditorinstance',
    plugins: 'code table lists image link',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | image link | code | table'
  });
</script>

<script>
// TinyMCE global initialization function
window.initTinyMCE = function() {
    if (window.tinymce) {
        tinymce.init({
            selector: 'textarea#myeditorinstance',
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount',
                'emoticons', 'codesample', 'code', 'visualchars', 'quickbars'
            ],
            toolbar: [
                'undo redo | blocks | fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist | image media link codesample table | removeformat | code fullscreen',
            ],
            menubar: 'file edit view insert format tools table help',
            branding: false,
            images_upload_url: window.tinymceUploadUrl || '',
            images_upload_credentials: true,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            file_picker_types: 'image',
            image_title: true,
            automatic_uploads: true,
            images_reuse_filename: true,
            content_style: 'body { font-family: Figtree, Arial, sans-serif; font-size: 16px; line-height: 1.6; margin: 1rem; }',
            // Responsive configuration
            mobile: {
                theme: 'silver',
                plugins: 'image link lists code bold italic',
                toolbar: 'undo redo | bold italic | image link | bullist numlist',
                menubar: false
            },
            // Responsive breakpoints
            min_height: 200,
            max_height: 600,
            autoresize_bottom_margin: 16,
            resize: 'both',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
                // Make editor responsive on window resize
                window.addEventListener('resize', function() {
                    var container = editor.getContainer();
                    if (container && window.innerWidth < 768) {
                        editor.settings.toolbar = 'undo redo | bold italic | image link';
                    } else {
                        editor.settings.toolbar = 'undo redo | blocks | fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist | image media link codesample table | removeformat | code fullscreen';
                    }
                });
            },
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', window.tinymceUploadUrl || '');
                var token = document.querySelector('meta[name="csrf-token"]');
                if (token) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                }
                xhr.onload = function() {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
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
};

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initTinyMCE === 'function') {
        window.initTinyMCE();
    }
});
</script>
