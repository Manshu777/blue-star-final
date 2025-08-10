<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;

class PhotoController extends Controller {
    protected $s3;
    protected $rekognition;

    public function __construct() {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $this->rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function index() {
        return Photo::with(['photographer', 'event'])->get();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png',
            'photographer_id' => 'required|exists:photographers,id',
            'event_id' => 'required|exists:events,id'
        ]);

        $file = $request->file('file');
        $path = "blue-star-media/" . date('Y') . "/{$validated['event_id']}/{$validated['photographer_id']}/" . $file->getClientOriginalName();
        $this->s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $path,
            'Body' => fopen($file->getRealPath(), 'r'),
            'ACL' => 'public-read',
        ]);

        $photo = Photo::create([
            'url' => $this->s3->getObjectUrl(env('AWS_BUCKET'), $path),
            's3_key' => $path,
            'photographer_id' => $validated['photographer_id'],
            'event_id' => $validated['event_id'],
            'uploaded_at' => now(),
        ]);

        // Facial recognition
        $result = $this->rekognition->detectFaces([
            'Image' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $path,
                ],
            ],
        ]);

        return response()->json($photo, 201);
    }

    public function show($id) {
        return Photo::with(['photographer', 'event', 'tags'])->findOrFail($id);
    }
}