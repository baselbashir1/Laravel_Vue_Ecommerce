<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'title' => fake()->text(),
        //     'image' => fake()->imageUrl(),
        //     'description' => fake()->realText(2000),
        //     'price' => fake()->randomFloat(2, 2, 5),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        //     'created_by' => 1,
        //     'updated_by' => 1,
        // ];

        $files = Storage::files('public/images');
        $randomFile = Arr::random($files);
        $imageUrl = Storage::url($randomFile);

        return [
            'title' => 'HyperX Cloud II - Gaming Headset',
            'image' => $imageUrl,
            'price' => '69.00',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
