<?php

namespace Database\Factories;

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

        return [
            'title' => 'HyperX Cloud II - Gaming Headset',
            'image' => 'https://cdn.shopify.com/s/files/1/0561/8345/5901/products/hyperx_cloud_ii_red_2_main_mixer_900x.jpg?v=1680559410',
            // 'image' => $this->faker->image('public/storage/images', 300, 300),
            // 'image' => Storage::allFiles('public/storage/images/'),
            'price' => '69.00',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
