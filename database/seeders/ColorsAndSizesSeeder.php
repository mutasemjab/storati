<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;
use App\Models\Size;

class ColorsAndSizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed Colors
        $colors = [
            'Red',
            'Blue', 
            'Green',
            'Black',
            'White',
            'Yellow',
            'Orange',
            'Purple',
            'Pink',
            'Brown',
            'Gray',
            'Navy',
            'Maroon',
            'Turquoise',
            'Gold',
            'Silver'
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['name' => $color]);
        }

        // Seed Sizes
        $sizes = [
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
            'XXXL',
            '36',
            '37',
            '38',
            '39',
            '40',
            '41',
            '42',
            '43',
            '44',
            '45',
            '46',
            'One Size'
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(['name' => $size]);
        }
    }
}