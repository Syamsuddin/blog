@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-12">
    <h1 class="mb-4">Dashboard</h1>
    <div class="alert alert-success">You're logged in!</div>
  </div>
</div>

@can('admin')
<div class="row mt-4">
  <div class="col-md-4 mb-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Posts</h5>
        <p class="card-text">Manage blog posts</p>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">Manage Posts</a>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Categories</h5>
        <p class="card-text">Manage post categories</p>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Manage Categories</a>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Tags</h5>
        <p class="card-text">Manage post tags</p>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-primary">Manage Tags</a>
      </div>
    </div>
  </div>
</div>
@endcan
@endsection
