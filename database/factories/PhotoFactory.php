<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // or null if you want only photographer_id
            'photographer_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image_path' => 'images/sample.jpg',
            'original_path' => 'images/original/sample.jpg',
            'watermarked_path' => 'images/watermarked/sample.jpg',
            'price' => $this->faker->randomFloat(2, 5, 50),
            'is_featured' => $this->faker->boolean(30),
            'license_type' => $this->faker->randomElement(['standard', 'extended', 'exclusive']),
            'tags' => implode(',', $this->faker->words(5)),
            'metadata' => [
                'camera' => $this->faker->word(),
                'lens' => $this->faker->word(),
                'resolution' => $this->faker->randomElement(['1920x1080', '4K', '8K']),
            ],
            'tour_provider' => $this->faker->company(),
            'location' => $this->faker->city(),
            'event' => $this->faker->sentence(2),
            'date' => $this->faker->dateTimeBetween('-1 year'),
            'file_size' => $this->faker->randomFloat(2, 0.5, 20), // in MB
            'is_sold' => false,
        ];
    }
}
