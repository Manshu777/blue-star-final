@extends('layouts.app')

@section('title', 'My Albums')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">My Albums</h2>

    <a href="{{ route('albums.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Create New Album
    </a>

    @if($albums->isEmpty())
        <div class="alert alert-info">You have not created any albums yet.</div>
    @else
        <div class="row">
            @foreach($albums as $album)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{ $album->cover_photo_url ?? asset('images/default_album_cover.jpg') }}" 
                             class="card-img-top" alt="Album Cover" style="height:200px; object-fit:cover;">

                        <div class="card-body">
                            <h5 class="card-title">{{ $album->name }}</h5>
                            <p class="card-text">{{ Str::limit($album->description, 80) }}</p>
                        </div>

                        <div class="card-footer text-center">
                            <a href="{{ route('albums.show', $album->id) }}" class="btn btn-info btn-sm me-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('albums.edit', $album->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('albums.destroy', $album->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this album?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
