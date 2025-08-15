<?php
namespace Database\Seeders;
use App\Models\Merchandise;
use Illuminate\Database\Seeder;

class MerchandiseSeeder extends Seeder
{
    public function run()
    {
      Merchandise::create([
            'name' => 'Custom Photo Mug',
            'description' => 'A personalized ceramic mug with your photo.',
            'image_path' => 'storage/merchandise/mug.jpg',
            'price' => 19.99,
            'stock' => 100,
            'is_featured' => true,
        ]);
        Merchandise::create([
            'name' => 'Photo Canvas Print',
            'description' => 'High-quality canvas print of your photo.',
            'image_path' => 'storage/merchandise/canvas.jpg',
            'price' => 49.99,
            'stock' => 50,
            'is_featured' => true,
        ]);
    }
}