@extends('layouts.app')

@section('content')
    <h1>My Secure Albums</h1>
    <!-- Assume $albums from controller; for simplicity, list user photos as one album -->
    @php
        $albums = ['Personal' => auth()->user()->photos]; // Expand with Album model
    @endphp
    @foreach ($albums as $name => $photos)
        <div>
            <h2>{{ $name }}</h2>
            @foreach ($photos as $photo)
                <img src="{{ Storage::disk('s3')->url($photo->image_path) }}" alt="{{ $photo->title }}" width="150">
            @endforeach
            <!-- Share album form -->
            <form method="POST" action="/albums/share"> <!-- Add route -->
                @csrf
                <input type="email" name="share_with" placeholder="Share with email">
                <button>Share Album</button>
            </form>
        </div>
    @endforeach
@endsection