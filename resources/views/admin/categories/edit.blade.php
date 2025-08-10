@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-edit text-primary me-2"></i>Edit Category</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Categories
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.categories.update', $category) }}">
                @csrf 
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag me-1"></i>Category Name
                    </label>
                    <input type="text" 
                           id="name"
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $category->name) }}" 
                           placeholder="Enter category name"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-link me-1"></i>Current Slug
                    </label>
                    <input type="text" class="form-control" value="{{ $category->slug }}" readonly>
                    <div class="form-text">
                        The slug will be automatically updated based on the name.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
