@extends('layouts.app')

@section('content')
    <h1>Share Photo: {{ $photo->title }}</h1>
    <img src="{{ $shareLink }}" alt="Share Preview" width="300">
    <p>Share Link (expires in 60 min): <a href="{{ $shareLink }}">{{ $shareLink }}</a></p>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareLink) }}" target="_blank">Share on Facebook</a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareLink) }}" target="_blank">Share on X</a>
    <!-- Add Instagram, etc. -->
    <form method="POST" action="{{ route('photos.share', $photo->id) }}"> <!-- For custom watermark -->
        @csrf
        <button name="add_watermark" value="1">Add Custom Watermark Before Sharing</button>
    </form>
@endsection