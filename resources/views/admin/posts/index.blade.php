@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Posts</h1>
  <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">New Post</a>
</div>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<table class="table table-striped">
  <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Updated</th><th></th></tr></thead>
  <tbody>
  @foreach($posts as $post)
    <tr>
      <td><a href="{{ route('admin.posts.edit',$post) }}">{{ $post->title }}</a></td>
      <td>{{ $post->category->name ?? '-' }}</td>
      <td>{{ $post->is_published ? 'Published' : 'Draft' }}</td>
      <td>{{ $post->updated_at->diffForHumans() }}</td>
      <td class="text-end">
        <form method="post" action="{{ route('admin.posts.destroy',$post) }}" onsubmit="return confirm('Delete this post?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Delete</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>

<div class="mt-3 d-flex justify-content-center">
    {{ $posts->appends(request()->query())->links('pagination.admin') }}
</div>

@endsection
