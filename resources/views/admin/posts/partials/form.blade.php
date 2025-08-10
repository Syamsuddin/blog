<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-lg" value="{{ old('title', $post->title ?? '') }}" required placeholder="Enter post title...">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Excerpt</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="3" placeholder="Brief description of the post...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            <small class="form-text text-muted">A brief summary that will appear in post listings.</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Body <span class="text-danger">*</span></label>
            <textarea id="body" name="body" class="form-control" rows="15" required placeholder="Write your post content here...">{{ old('body', $post->body ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Post Settings</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $post->category_id ?? '')==$cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tags</label>
                    <div class="border rounded p-2" style="max-height: 120px; overflow-y: auto;">
                        @foreach($tags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                       id="tag-{{ $tag->id }}" @checked(in_array($tag->id, old('tags', isset($post) ? $post->tags->pluck('id')->all() : [])))>
                                <label class="form-check-label" for="tag-{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="publish_now" value="1" id="publish_now">
                    <label class="form-check-label fw-bold" for="publish_now">
                        <i class="fas fa-rocket me-1"></i>Publish now
                    </label>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-image me-2"></i>Featured Image</h6>
            </div>
            <div class="card-body">
                @if(!empty($post->featured_image))
                    <div class="mb-3 text-center">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="Current featured image" 
                             class="img-fluid rounded border" style="max-height: 150px;">
                        <p class="small text-muted mt-1">Current featured image</p>
                    </div>
                @endif
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                <small class="form-text text-muted">Upload JPG, PNG, or WebP image. Max size: 2MB.</small>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-search me-2"></i>SEO Settings</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta['meta_title'] ?? '') }}" 
                           placeholder="SEO title for search engines...">
                    <small class="form-text text-muted">Recommended: 50-60 characters</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3" 
                              placeholder="SEO description for search engines...">{{ old('meta_description', $post->meta['meta_description'] ?? '') }}</textarea>
                    <small class="form-text text-muted">Recommended: 150-160 characters</small>
                </div>
            </div>
        </div>
    </div>
</div>
@push('head')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<style>
.cke {
    border-radius: 0.375rem !important;
}
.cke_contents {
    min-height: 300px !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for CKEditor to load
    function initCKEditor() {
        if (typeof CKEDITOR !== 'undefined') {
            // Initialize CKEditor for body
            if (document.getElementById('body')) {
                try {
                    CKEDITOR.replace('body', {
                        height: 400,
                        toolbar: [
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                            { name: 'links', items: ['Link', 'Unlink'] },
                            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
                            '/',
                            { name: 'styles', items: ['Format'] },
                            { name: 'tools', items: ['Maximize'] },
                            { name: 'document', items: ['Source'] }
                        ],
                        format_tags: 'p;h1;h2;h3;h4;h5;h6;pre',
                        removeDialogTabs: 'image:advanced;link:advanced'
                    });
                    console.log('CKEditor initialized successfully');
                } catch (error) {
                    console.warn('CKEditor failed to initialize:', error);
                }
            }
        } else {
            console.warn('CKEditor not loaded');
        }
    }
    
    // Try to initialize immediately, or wait a bit
    if (typeof CKEDITOR !== 'undefined') {
        initCKEditor();
    } else {
        setTimeout(initCKEditor, 1000);
    }
    
    // Handle form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            // Update textarea with CKEditor content before submit
            try {
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['body']) {
                    CKEDITOR.instances['body'].updateElement();
                }
            } catch (error) {
                console.warn('Error updating CKEditor content:', error);
            }
        });
    }
});
</script>
@endpush
