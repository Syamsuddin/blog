@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-tags text-primary me-2"></i>Tags</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Tag
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts Count</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>
                                <a href="{{ route('admin.tags.edit', $tag) }}" class="text-decoration-none">
                                    <span class="badge bg-primary">{{ $tag->name }}</span>
                                </a>
                            </td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td>
                                <span class="badge bg-secondary">{{ $tag->posts_count ?? 0 }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="post" action="{{ route('admin.tags.destroy', $tag) }}" class="d-inline" onsubmit="return confirm('Delete this tag?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($tags->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No tags found. Create your first tag!</p>
                    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Tag
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if(!$tags->isEmpty())
        <div class="mt-3 d-flex justify-content-center">
            {{ $tags->appends(request()->query())->links('pagination.admin') }}
        </div>
    @endif
</div>
@endsection
