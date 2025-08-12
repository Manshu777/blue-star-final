{{-- resources/views/user/photos/preview.blade.php --}}
@extends('layouts.app')

@section('title', 'Photo Preview')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Photo Preview</h2>

        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="preview-container position-relative d-inline-block">
                    <img src="{{ $photo->url }}" alt="Preview" class="img-fluid" style="max-height:500px; opacity:0.8;">

                    {{-- Watermark Overlay --}}
                    <div class="watermark position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                        <span class="display-4 fw-bold text-muted" style="opacity: 0.3; transform: rotate(-30deg);">
                            SAMPLE
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-footer text-center">
                <a href="{{ route('photos.download', $photo->id) }}" class="btn btn-success me-2">
                    <i class="fas fa-download"></i> Download Without Watermark
                </a>
                <a href="{{ route('store.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Buy Prints
                </a>
            </div>
        </div>
    </div>

    <style>
        .preview-container {
            position: relative;
        }

        .watermark {
            top: 0;
            left: 0;
            pointer-events: none;
        }
    </style>
@endsection