@extends('layouts.app')

@section('content')
    <h1>Search & Retrieve Photos</h1>
    <form method="GET" action="{{ route('photos.search') }}">
        <input type="text" name="keyword" placeholder="Keywords, tags, or face match" value="{{ request('keyword') }}">
        <input type="date" name="date_from" placeholder="From Date" value="{{ request('date_from') }}">
        <!-- Add more filters: location, photographer -->
        <button type="submit">Search</button>
    </form>
    @foreach ($photos as $photo)
        <div>
            <img src="{{ Storage::disk('s3')->url($photo->watermarked_path ?? $photo->image_path) }}" alt="{{ $photo->title }}" width="150">
            <p>{{ $photo->title }} - Price: ${{ $photo->price }}</p>
            <a href="{{ route('photos.show', $photo->id) }}">View Details</a>
            <a href="{{ route('photos.edit', $photo->id) }}">Edit</a>
            <a href="{{ route('photos.share', $photo->id) }}">Share</a>
        </div>
    @endforeach
@endsection