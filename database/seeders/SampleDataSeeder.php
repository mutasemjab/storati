<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Category;
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
        // Create sample celebrities
        $this->createSampleCelebrities();
        
        // Create sample brands
        $this->createSampleBrands();
        
        // Create sample shops
        $this->createSampleShops();
        
        // Create sample categories
        $this->createSampleCategories();
        
        $this->command->info('Sample data created successfully!');
    }

    /**
     * Create sample celebrities
     */
    private function createSampleCelebrities()
    {
        $celebrities = [
            [
                'name_en' => 'Will Smith',
                'name_ar' => 'ويل سميث',
                'photo' => 'sample_celebrity_male.jpg',
                'gender' => 'man'
            ],
            [
                'name_en' => 'Emma Watson',
                'name_ar' => 'إيما واتسون',
                'photo' => 'sample_celebrity_female.jpg',
                'gender' => 'woman'
            ],
            [
                'name_en' => 'Ryan Reynolds',
                'name_ar' => 'رايان رينولدز',
                'photo' => 'sample_celebrity_both.jpg',
                'gender' => 'both'
            ]
        ];

        foreach ($celebrities as $celebrity) {
            Celebrity::firstOrCreate(
                ['name_en' => $celebrity['name_en']],
                [
                    'name_ar' => $celebrity['name_ar'],
                    'photo' => $celebrity['photo'],
                    'gender' => $celebrity['gender']
                ]
            );
            
            $this->command->info('Sample celebrity created: ' . $celebrity['name_en'] . ' (Gender: ' . $celebrity['gender'] . ')');
        }
    }

    /**
     * Create sample brands
     */
    private function createSampleBrands()
    {
        $brands = [
            [
                'name_en' => 'Nike',
                'name_ar' => 'نايك',
                'photo' => 'sample_brand_both.jpg',
                'gender' => 'both'
            ],
            [
                'name_en' => 'Victoria\'s Secret',
                'name_ar' => 'فيكتوريا سيكريت',
                'photo' => 'sample_brand_women.jpg',
                'gender' => 'woman'
            ],
            [
                'name_en' => 'Hugo Boss',
                'name_ar' => 'هوجو بوس',
                'photo' => 'sample_brand_men.jpg',
                'gender' => 'man'
            ],
            [
                'name_en' => 'Adidas',
                'name_ar' => 'أديداس',
                'photo' => 'sample_brand_unisex.jpg',
                'gender' => 'both'
            ]
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['name_en' => $brand['name_en']],
                [
                    'name_ar' => $brand['name_ar'],
                    'photo' => $brand['photo'],
                    'gender' => $brand['gender']
                ]
            );
            
            $this->command->info('Sample brand created: ' . $brand['name_en'] . ' (Gender: ' . $brand['gender'] . ')');
        }
    }

    /**
     * Create sample shops
     */
    private function createSampleShops()
    {
        $shops = [
            [
                'name_en' => 'Fashion Store',
                'name_ar' => 'متجر الأزياء',
                'photo' => 'sample_shop_both.jpg',
                'gender' => 'both'
            ],
            [
                'name_en' => 'Men\'s Warehouse',
                'name_ar' => 'مستودع الرجال',
                'photo' => 'sample_shop_men.jpg',
                'gender' => 'man'
            ],
            [
                'name_en' => 'Ladies Boutique',
                'name_ar' => 'بوتيك السيدات',
                'photo' => 'sample_shop_women.jpg',
                'gender' => 'woman'
            ],
            [
                'name_en' => 'Universal Mall',
                'name_ar' => 'المول العالمي',
                'photo' => 'sample_shop_universal.jpg',
                'gender' => 'both'
            ]
        ];

        foreach ($shops as $shop) {
            Shop::firstOrCreate(
                ['name_en' => $shop['name_en']],
                [
                    'name_ar' => $shop['name_ar'],
                    'photo' => $shop['photo'],
                    'gender' => $shop['gender']
                ]
            );
            
            $this->command->info('Sample shop created: ' . $shop['name_en'] . ' (Gender: ' . $shop['gender'] . ')');
        }
    }

    /**
     * Create sample categories
     */
    private function createSampleCategories()
    {
        $categories = [
            [
                'name_en' => 'Clothing',
                'name_ar' => 'الملابس',
                'photo' => 'sample_category_clothing.jpg',
                'gender' => 'both',
                'category_id' => null
            ],
            [
                'name_en' => 'Men\'s Shirts',
                'name_ar' => 'قمصان الرجال',
                'photo' => 'sample_category_mens_shirts.jpg',
                'gender' => 'man',
                'category_id' => 1 // Will be updated after parent is created
            ],
            [
                'name_en' => 'Women\'s Dresses',
                'name_ar' => 'فساتين النساء',
                'photo' => 'sample_category_womens_dresses.jpg',
                'gender' => 'woman',
                'category_id' => 1 // Will be updated after parent is created
            ],
            [
                'name_en' => 'Shoes',
                'name_ar' => 'الأحذية',
                'photo' => 'sample_category_shoes.jpg',
                'gender' => 'both',
                'category_id' => null
            ],
            [
                'name_en' => 'Accessories',
                'name_ar' => 'الإكسسوارات',
                'photo' => 'sample_category_accessories.jpg',
                'gender' => 'both',
                'category_id' => null
            ]
        ];

        foreach ($categories as $index => $category) {
            $createdCategory = Category::firstOrCreate(
                ['name_en' => $category['name_en']],
                [
                    'name_ar' => $category['name_ar'],
                    'photo' => $category['photo'],
                    'gender' => $category['gender'],
                    'category_id' => $category['category_id']
                ]
            );

            // Update subcategories with correct parent ID
            if ($category['name_en'] === 'Clothing') {
                Category::where('name_en', 'Men\'s Shirts')
                    ->update(['category_id' => $createdCategory->id]);
                Category::where('name_en', 'Women\'s Dresses')
                    ->update(['category_id' => $createdCategory->id]);
            }
            
            $this->command->info('Sample category created: ' . $category['name_en'] . ' (Gender: ' . $category['gender'] . ')');
        }
    }
}
