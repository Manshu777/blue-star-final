@extends('layouts.app')

@section('content')
    <h1>Edit Photo: {{ $photo->title }}</h1>
    <img src="{{ Storage::disk('s3')->url($photo->image_path) }}" alt="Original" width="300">
    <form method="POST" action="{{ route('photos.edit', $photo->id) }}">
        @csrf
        <label>
            Brightness: <input type="number" name="brightness" min="-100" max="100">
        </label>
        <button name="sharpen" value="1">Apply Sharpen</button>
        <button name="crop" value="1">Crop (Example)</button>
        <label>
            AR Overlay Text: <input type="text" name="overlay">
        </label>
        <button type="submit">Save Edits</button>
    </form>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
@endsection