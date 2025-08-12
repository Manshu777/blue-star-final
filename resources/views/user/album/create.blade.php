@extends('layouts.app')

@section('title', 'Create Album')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Create New Album</h2>

    <form action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Album Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Album Description</label>
            <textarea name="description" id="description" rows="4" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="cover_photo" class="form-label">Cover Photo</label>
            <input type="file" name="cover_photo" id="cover_photo" class="form-control">
        </div>

        <div class="mb-3">
            <label for="shared_with" class="form-label">Share With (Emails, comma-separated)</label>
            <input type="text" name="shared_with" id="shared_with" class="form-control" 
                   placeholder="example1@mail.com, example2@mail.com">
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Create Album
        </button>
        <a href="{{ route('albums.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
