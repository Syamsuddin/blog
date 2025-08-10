<!-- Recent Posts Widget -->
<div class="sidebar-widget">
    <h5><i class="fas fa-clock"></i> Recent Posts</h5>
    @php
        $recentPosts = \App\Models\Post::published()->latest('published_at')->take(5)->get();
    @endphp
    @foreach($recentPosts as $post)
        <div class="d-flex mb-3">
            @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover; margin-right: 1rem;">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; margin-right: 1rem;">
                    <i class="fas fa-image text-muted"></i>
                </div>
            @endif
            <div>
                <h6 class="mb-1" style="font-size: 0.9rem;">
                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">{{ Str::limit($post->title, 40) }}</a>
                </h6>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> {{ $post->published_at?->format('M d, Y') }}
                </small>
            </div>
        </div>
    @endforeach
</div>

<!-- Categories Widget -->
<div class="sidebar-widget">
    <h5><i class="fas fa-folder"></i> Categories</h5>
    @php
        $categories = \App\Models\Category::withCount('posts')->orderBy('posts_count', 'desc')->get();
    @endphp
    <ul class="list-unstyled">
        @foreach($categories as $category)
            <li class="mb-2">
                <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                    <span>{{ $category->name }}</span>
                    <span class="badge bg-secondary">{{ $category->posts_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<!-- Popular Tags Widget -->
<div class="sidebar-widget">
    <h5><i class="fas fa-tags"></i> Popular Tags</h5>
    @php
        $tags = \App\Models\Tag::withCount('posts')->orderBy('posts_count', 'desc')->take(15)->get();
    @endphp
    <div class="tag-cloud">
        @foreach($tags as $tag)
            <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="badge bg-light text-dark me-1 mb-2 text-decoration-none" style="font-size: 0.9rem;">
                {{ $tag->name }} ({{ $tag->posts_count }})
            </a>
        @endforeach
    </div>
</div>

<!-- About Widget -->
<div class="sidebar-widget">
    <h5><i class="fas fa-info-circle"></i> About This Blog</h5>
    <p style="font-size: 0.9rem; line-height: 1.6;">
        Welcome to {{ blog_title() }}! {{ blog_description() }}
    </p>
    <a href="#about" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-arrow-right"></i> Learn More
    </a>
</div>

<!-- Archive Widget -->
<div class="sidebar-widget">
    <h5><i class="fas fa-archive"></i> Archives</h5>
    @php
        $archives = \App\Models\Post::published()
            ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
    @endphp
    <ul class="list-unstyled">
        @foreach($archives as $archive)
            <li class="mb-2">
                <a href="{{ route('posts.index', ['year' => $archive->year, 'month' => $archive->month]) }}" class="text-decoration-none d-flex justify-content-between">
                    <span>{{ DateTime::createFromFormat('!m', $archive->month)->format('F') }} {{ $archive->year }}</span>
                    <span class="badge bg-secondary">{{ $archive->count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
