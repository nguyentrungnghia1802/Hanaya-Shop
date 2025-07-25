// Modal Alpine.js component - CSP compliant
window.ModalComponent = () => ({
    show: false,
    
    focusables() {
        // All focusable element types...
        let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
        return [...this.$el.querySelectorAll(selector)]
            // All non-disabled elements...
            .filter(el => !el.hasAttribute('disabled'));
    },
    
    firstFocusable() { 
        return this.focusables()[0]; 
    },
    
    lastFocusable() { 
        return this.focusables().slice(-1)[0]; 
    },
    
    nextFocusable() { 
        return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); 
    },
    
    prevFocusable() { 
        return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); 
    },
    
    nextFocusableIndex() { 
        return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); 
    },
    
    prevFocusableIndex() { 
        return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; 
    },
    
    init() {
        this.$watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-y-hidden');
                // Focus first focusable element if modal is focusable
                if (this.$el.hasAttribute('data-focusable')) {
                    setTimeout(() => {
                        const first = this.firstFocusable();
                        if (first) first.focus();
                    }, 100);
                }
            } else {
                document.body.classList.remove('overflow-y-hidden');
            }
        });
    },
    
    handleKeydownTab(event) {
        if (event.shiftKey) {
            return;
        }
        event.preventDefault();
        const next = this.nextFocusable();
        if (next) next.focus();
    },
    
    handleKeydownShiftTab(event) {
        event.preventDefault();
        const prev = this.prevFocusable();
        if (prev) prev.focus();
    }
});

// Navigation Alpine.js component - CSP compliant
window.NavigationComponent = () => ({
    open: false,
    loading: false,
    
    // Helper methods for class conditionals - CSP compliant
    getHamburgerIconClass() {
        return this.open ? 'hidden' : 'inline-flex';
    },
    
    getCloseIconClass() {
        return this.open ? 'inline-flex' : 'hidden';
    },
    
    getResponsiveMenuClass() {
        return this.open ? 'block' : 'hidden';
    }
});

// Dropdown Alpine.js component - CSP compliant
window.DropdownComponent = () => ({
    open: false
});

// TinyMCE configuration function
window.initTinyMCE = function(selector = '.description') {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: selector,
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'image link | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            branding: false,
            promotion: false,
            // Image upload configuration
            images_upload_url: '/admin/posts/upload-image',
            images_upload_handler: function (blobInfo, success, failure, progress) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/admin/posts/upload-image');
                
                // Add CSRF token
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };
                
                xhr.onload = function() {
                    var json;
                    if (xhr.status === 403) {
                        failure('HTTP Error: ' + xhr.status, { remove: true });
                        return;
                    }
                    
                    if (xhr.status < 200 || xhr.status >= 300) {
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
                
                xhr.onerror = function () {
                    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };
                
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                
                xhr.send(formData);
            },
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };
                
                input.click();
            },
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('TinyMCE initialized successfully with image upload');
                });
            }
        });
    } else {
        console.error('TinyMCE is not loaded');
    }
};

// Image preview function
window.initImagePreview = function() {
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    
    if (imageInput && previewImage) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
};

// Banner Swiper initialization function
window.initBannerSwiper = function() {
    // Check if Swiper is loaded
    if (typeof Swiper !== 'undefined') {
        const bannerSwiper = new Swiper('.bannerSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
        });
    } else {
        console.error('Swiper is not loaded');
    }
};
