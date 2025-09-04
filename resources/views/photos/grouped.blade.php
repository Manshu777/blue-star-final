@extends('layouts.app')

@section('content')
    <h1>Grouped Photo Albums</h1>
    @foreach ($groups as $group)
        <div class="album">
            <h2>{{ $group->tour_provider ?? 'Unknown Provider' }} - {{ $group->location ?? 'Unknown Location' }} ({{ $group->date ?? 'Unknown Date' }}) - {{ $group->event ?? 'No Event' }}</h2>
            @php
                $photosInGroup = App\Models\Photo::where('tour_provider', $group->tour_provider)
                    ->where('location', $group->location)
                    ->where('date', $group->date)
                    ->where('event', $group->event)
                    ->get();
            @endphp
            @foreach ($photosInGroup as $photo)
                <img src="{{ Storage::disk('s3')->url($photo->watermarked_path ?? $photo->image_path) }}" alt="{{ $photo->title }}" width="150">
            @endforeach
        </div>
    @endforeach
@endsection