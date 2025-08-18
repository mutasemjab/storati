<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Celebrity;
use Illuminate\Support\Facades\File;


class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create upload directories if they don't exist
        $this->createUploadDirectories();
        
        // Create sample celebrity
        $this->createSampleCelebrity();
        
        // Create sample brand
        $this->createSampleBrand();
        
        // Create sample shop
        $this->createSampleShop();
        
        $this->command->info('Sample data created successfully!');
    }

    /**
     * Create upload directories
     */
    private function createUploadDirectories()
    {
        $directories = [
            'assets/admin/uploads/',
            'assets/admin/uploads/',
            'assets/admin/uploads/'
        ];

        foreach ($directories as $directory) {
            $fullPath = base_path($directory);
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
                $this->command->info("Created directory: {$directory}");
            }
        }
    }

    /**
     * Create sample celebrity
     */
    private function createSampleCelebrity()
    {
        Celebrity::firstOrCreate(
            ['name_en' => 'Will Smith'],
            [
                'name_ar' => 'ويل سميث',
                'photo' => 'assets/admin/uploads/sample_celebrity.jpg'
            ]
        );

        $this->command->info('Sample celebrity created: Will Smith');
    }

    /**
     * Create sample brand
     */
    private function createSampleBrand()
    {
        Brand::firstOrCreate(
            ['name_en' => 'Nike'],
            [
                'name_ar' => 'نايك',
                'photo' => 'assets/admin/uploads/sample_brand.jpg'
            ]
        );

        $this->command->info('Sample brand created: Nike');
    }

    /**
     * Create sample shop
     */
    private function createSampleShop()
    {
        Shop::firstOrCreate(
            ['name_en' => 'Fashion Store'],
            [
                'name_ar' => 'متجر الأزياء',
                'photo' => 'assets/admin/uploads/sample_shop.jpg'
            ]
        );

        $this->command->info('Sample shop created: Fashion Store');
    }
}
