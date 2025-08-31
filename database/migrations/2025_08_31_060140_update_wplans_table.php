<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('plans', function (Blueprint $table) {
            $table->float('monthly_price')->after('name');
            $table->float('yearly_price')->after('monthly_price');
            $table->string('billing_cycle')->default('monthly')->after('yearly_price'); // 'monthly' or 'yearly'
            $table->boolean('is_active')->default(true)->after('description');
        });

        \App\Models\Plan::upsert([
            [
                'name' => 'Free',
                'price' => 0.00, // Match monthly_price if keeping price
                'monthly_price' => 0.00,
                'yearly_price' => 0.00,
                'billing_cycle' => 'monthly',
                'storage_limit' => 1,
                'photo_upload_limit' => 5,
                'facial_recognition_enabled' => true,
                'merchandise_enabled' => false,
                'description' => json_encode([
                    'Basic facial recognition (5 searches/day)',
                    'Photo grouping by date and time',
                    'Basic search with keywords and metadata',
                    'Watermarked photo previews',
                    '1GB cloud storage',
                    'Offline mode for pre-downloaded content',
                    'Basic social sharing with watermarks',
                    'E-commerce access (no discounts)',
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic',
                'price' => 4.99, // Match monthly_price
                'monthly_price' => 4.99,
                'yearly_price' => 49.90,
                'billing_cycle' => 'monthly',
                'storage_limit' => 10,
                'photo_upload_limit' => 0,
                'facial_recognition_enabled' => true,
                'merchandise_enabled' => true,
                'description' => json_encode([
                    'Unlimited facial recognition searches',
                    'Full photo grouping (Tour Provider, Location, Date, Time, Events)',
                    'Advanced search filters (date range, photographer, location)',
                    'Basic photo enhancement (cropping, filters, brightness)',
                    '10GB cloud storage with private albums',
                    'Social sharing without watermarks',
                    'In-app/website purchases',
                    'Instant notifications',
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'price' => 9.99, // Match monthly_price
                'monthly_price' => 9.99,
                'yearly_price' => 99.90,
                'billing_cycle' => 'monthly',
                'storage_limit' => 0,
                'photo_upload_limit' => 0,
                'facial_recognition_enabled' => true,
                'merchandise_enabled' => true,
                'description' => json_encode([
                    'All Basic Plan features',
                    'AI-powered enhancements (sharpening, color correction, background removal)',
                    'AR stickers, overlays, and full editing tools',
                    'Unlimited cloud storage with encryption',
                    'Photo collaboration for shared albums',
                    'Location-based services (GPS tagging)',
                    '20% discount on personalized merchandise',
                    'Personal photo trend analytics',
                    'Offline access to all content',
                    'AR experiences on mobile',
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['name'], [
            'price',
            'monthly_price',
            'yearly_price',
            'billing_cycle',
            'storage_limit',
            'photo_upload_limit',
            'facial_recognition_enabled',
            'merchandise_enabled',
            'description',
            'is_active',
            'updated_at',
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
