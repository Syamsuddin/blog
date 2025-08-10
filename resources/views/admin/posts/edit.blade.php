@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit text-primary me-2"></i>Edit Post</h1>
        <div>
            <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn btn-outline-info me-2">
                <i class="fas fa-external-link-alt me-2"></i>View Post
            </a>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Posts
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
                @csrf 
                @method('PUT')
                @include('admin.posts.partials.form')
                
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
