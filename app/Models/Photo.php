<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'photographer_id',
        'title',
        'description',
        'image_path',
        'original_path',
        'watermarked_path',
        'price',
        'is_featured',
        'license_type',
        'tags',
        'metadata',
        'tour_provider',
        'location',
        'event',
        'date',
        'file_size',
        'is_sold',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean',
        'is_sold' => 'boolean',
        'date' => 'datetime',
        'file_size' => 'float',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user who uploaded the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the photographer (could differ from uploader).
     */
    public function photographer()
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    /**
     * Get the orders related to this photo.
     */
    public function orders()
    {
        return $this->morphMany(Order::class, 'item');
    }

    /**
     * Get the order items related to this photo.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor: Display correct image path (original if sold, watermarked otherwise).
     */
    public function getDisplayPathAttribute(): ?string
    {
        return $this->is_sold ? $this->original_path : $this->watermarked_path;
    }

    /**
     * Generate a watermark for the photo using Intervention Image.
     */
    public function generateWatermark(
        string $originalPath,
        string $watermarkPath = 'public/images/watermark.png'
    ): bool {
        try {
            $image = Image::make($originalPath);
            $watermark = Image::make(storage_path('app/' . $watermarkPath));

            // Resize watermark to 20% of the main image width
            $watermark->resize($image->width() * 0.2, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Insert watermark in the bottom-right corner
            $image->insert($watermark, 'bottom-right', 10, 10);

            // Save watermarked image
            $savePath = storage_path('app/public/' . $this->watermarked_path);
            $image->save($savePath, 85, 'jpg'); // 85% quality for optimization

            return true;
        } catch (\Exception $e) {
            \Log::error('Watermark generation failed: ' . $e->getMessage());
            return false;
        }
    }
}
