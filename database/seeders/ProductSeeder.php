<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variation;
use App\Models\Category;
use App\Models\Celebrity;
use App\Models\Brand;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Size;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample products for men
        $this->createMenProducts();
        
        // Create sample products for women
        $this->createWomenProducts();
        
        $this->command->info('Sample products created successfully!');
    }

    /**
     * Create sample products for men
     */
    private function createMenProducts()
    {
        $menProducts = [
            [
                'name_en' => 'Classic Men\'s Perfume',
                'name_ar' => 'عطر رجالي كلاسيكي',
                'description_en' => 'A premium quality classic Perfume perfect for formal and casual occasions. Made with high-quality cotton fabric for maximum comfort.',
                'description_ar' => 'قميص كلاسيكي عالي الجودة مثالي للمناسبات الرسمية والعادية. مصنوع من قماش القطن عالي الجودة لأقصى راحة.',
                'price' => 85.00,
                'discount_percentage' => 10,
                'gender' => 'man',
                'my_collabs' => 1,
                'is_featured' => 1,
                'image_prefix' => 'man1'
            ],
            [
                'name_en' => 'Casual Men\'s T-Shirt',
                'name_ar' => 'تي شيرت رجالي كاجوال',
                'description_en' => 'Comfortable and stylish casual t-shirt made from breathable fabric. Perfect for everyday wear.',
                'description_ar' => 'تي شيرت كاجوال مريح وأنيق مصنوع من قماش قابل للتنفس. مثالي للارتداء اليومي.',
                'price' => 45.00,
                'discount_percentage' => null,
                'gender' => 'man',
                'my_collabs' => 2,
                'is_featured' => 1,
                'image_prefix' => 'man2'
            ],
            [
                'name_en' => 'Men\'s Denim Watch',
                'name_ar' => 'ساعة رجالي دنيم',
                'description_en' => 'High-quality denim Watch with a modern fit. Durable and comfortable for all-day wear.',
                'description_ar' => 'ساعة دنيم عالي الجودة بقصة عصرية. متين ومريح للارتداء طوال اليوم.',
                'price' => 120.00,
                'discount_percentage' => 15,
                'gender' => 'man',
                'my_collabs' => 1,
                'is_featured' => 2,
                'image_prefix' => 'man3'
            ],
            [
                'name_en' => 'Men\'s Leather Watch',
                'name_ar' => 'ساعة رجالي جلد',
                'description_en' => 'Premium leather Watch with classic design. Perfect for adding style to any outfit.',
                'description_ar' => 'ساعة جلدي فاخر بتصميم كلاسيكي. مثالي لإضافة الأناقة لأي زي.',
                'price' => 250.00,
                'discount_percentage' => 20,
                'gender' => 'man',
                'my_collabs' => 1,
                'is_featured' => 1,
                'image_prefix' => 'man4'
            ],
            [
                'name_en' => 'Men\'s Sport t-shirt',
                'name_ar' => 'قميص رياضي رجالي',
                'description_en' => 'Comfortable sport t-shirt designed for active lifestyle. Excellent grip and cushioning.',
                'description_ar' => 'قميص رياضي مريح مصمم لنمط الحياة النشط. قبضة ووسادة ممتازة.',
                'price' => 95.00,
                'discount_percentage' => null,
                'gender' => 'man',
                'my_collabs' => 2,
                'is_featured' => 1,
                'image_prefix' => 'man5'
            ],
            [
                'name_en' => 'Men\'s Formal Suit',
                'name_ar' => 'بدلة رجالية رسمية',
                'description_en' => 'Elegant formal suit perfect for business meetings and special occasions. Tailored fit with premium fabric.',
                'description_ar' => 'بدلة رسمية أنيقة مثالية لاجتماعات العمل والمناسبات الخاصة. قصة مفصلة بقماش فاخر.',
                'price' => 350.00,
                'discount_percentage' => 25,
                'gender' => 'man',
                'my_collabs' => 1,
                'is_featured' => 1,
                'image_prefix' => 'man6'
            ]
        ];

        $this->createProductsWithVariations($menProducts);
    }

    /**
     * Create sample products for women
     */
    private function createWomenProducts()
    {
        $womenProducts = [
            [
                'name_en' => 'Elegant Women\'s ',
                'name_ar' => 'عقد نسائي أنيق',
                'description_en' => 'Beautiful elegant  perfect for special occasions. Made with high-quality fabric and attention to detail.',
                'description_ar' => 'عقد أنيق جميل مثالي للمناسبات الخاصة. مصنوع من قماش عالي الجودة مع الاهتمام بالتفاصيل.',
                'price' => 150.00,
                'discount_percentage' => 15,
                'gender' => 'woman',
                'my_collabs' => 1,
                'is_featured' => 1,
                'image_prefix' => 'woman1'
            ],
            [
                'name_en' => 'Casual Women\'s test',
                'name_ar' => 'عقد نسائية كاجوال',
                'description_en' => 'Comfortable and stylish test perfect for everyday wear. Versatile piece that goes with everything.',
                'description_ar' => 'عقد مريحة وأنيقة مثالية للارتداء اليومي. قطعة متعددة الاستخدامات تناسب كل شيء.',
                'price' => 65.00,
                'discount_percentage' => null,
                'gender' => 'woman',
                'my_collabs' => 2,
                'is_featured' => 1,
                'image_prefix' => 'woman2'
            ],
            [
                'name_en' => 'Women\'s Skinny makeup',
                'name_ar' => 'مكياج نسائي ضيق',
                'description_en' => 'Trendy skinny makeup with perfect fit. Made from stretch denim for comfort and style.',
                'description_ar' => 'مكياج ضيق عصري بقصة مثالية. مصنوع من دنيم مطاطي للراحة والأناقة.',
                'price' => 110.00,
                'discount_percentage' => 12,
                'gender' => 'woman',
                'my_collabs' => 1,
                'is_featured' => 2,
                'image_prefix' => 'woman3'
            ],
            [
                'name_en' => 'Women\'s Designer sensal',
                'name_ar' => ' سنسال نسائية مصممة',
                'description_en' => 'Luxury designer sensal made from premium leather. Perfect accessory for any outfit.',
                'description_ar' => 'سنسال يد مصممة فاخرة مصنوعة من الجلد الفاخر. إكسسوار مثالي لأي زي.',
                'price' => 280.00,
                'discount_percentage' => 18,
                'gender' => 'woman',
                'my_collabs' => 1,
                'is_featured' => 1,
                'image_prefix' => 'woman4'
            ],
            [
                'name_en' => 'Women\'s High bags',
                'name_ar' => 'شنتة عالمية نسائي',
                'description_en' => 'Elegant high bag shoes perfect for formal events. Comfortable design with stylish appearance.',
                'description_ar' => ' شنتة عالي أنيق مثالي للأحداث الرسمية. تصميم مريح بمظهر أنيق.',
                'price' => 125.00,
                'discount_percentage' => null,
                'gender' => 'woman',
                'my_collabs' => 2,
                'is_featured' => 1,
                'image_prefix' => 'woman5'
            ],
            [
                'name_en' => 'Women\'s Perfume',
                'name_ar' => 'عطر نسائي جميل',
                'description_en' => 'Stunning Perfume for special occasions. Luxurious fabric with elegant design.',
                'description_ar' => ' عطر نسائي جميل مذهل للمناسبات الخاصة. قماش فاخر بتصميم أنيق.',
                'price' => 450.00,
                'discount_percentage' => 30,
                'gender' => 'woman',
                'my_collabs' => 1,
                'is_featured' => 1,
                'image_prefix' => 'woman6'
            ]
        ];

        $this->createProductsWithVariations($womenProducts);
    }

    /**
     * Create products with their variations and images
     */
    private function createProductsWithVariations($products)
    {
        foreach ($products as $productData) {
            // Calculate price after discount
            $priceAfterDiscount = null;
            if ($productData['discount_percentage']) {
                $priceAfterDiscount = $productData['price'] * (1 - $productData['discount_percentage'] / 100);
            }

            // Get random related data
            $category = $this->getRandomCategory($productData['gender']);
            $celebrity = $this->getRandomCelebrity($productData['gender']);
            $brand = $this->getRandomBrand($productData['gender']);
            $shop = $this->getRandomShop($productData['gender']);

            // Create the product
            $product = Product::create([
                'name_en' => $productData['name_en'],
                'name_ar' => $productData['name_ar'],
                'description_en' => $productData['description_en'],
                'description_ar' => $productData['description_ar'],
                'price' => $productData['price'],
                'discount_percentage' => $productData['discount_percentage'],
                'price_after_discount' => $priceAfterDiscount,
                'gender' => $productData['gender'],
                'category_id' => $category ? $category->id : null,
                'celebrity_id' => $celebrity ? $celebrity->id : null,
                'brand_id' => $brand ? $brand->id : null,
                'shop_id' => $shop ? $shop->id : null,
                'my_collabs' => $productData['my_collabs'],
                'is_featured' => $productData['is_featured'],
            ]);

            // Create product images
            $this->createProductImages($product->id, $productData['image_prefix']);

            // Create product variations
            $this->createProductVariations($product->id);

            $this->command->info('Sample product created: ' . $productData['name_en']);
        }
    }

    /**
     * Create product images
     */
    private function createProductImages($productId, $imagePrefix)
    {
        // Create main product image
        ProductImage::create([
            'product_id' => $productId,
            'photo' => $imagePrefix . '.jpg'
        ]);

        // Create additional images (optional - you can adjust the number)
        for ($i = 1; $i <= 3; $i++) {
            ProductImage::create([
                'product_id' => $productId,
                'photo' => $imagePrefix . '_' . $i . '.jpg'
            ]);
        }
    }

    /**
     * Create product variations with random colors and sizes
     */
    private function createProductVariations($productId)
    {
        // Get random colors and sizes
        $colors = Color::inRandomOrder()->limit(rand(3, 5))->get();
        $sizes = Size::inRandomOrder()->limit(rand(3, 4))->get();

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                // Random price adjustment between -10 and +20
                $priceAdjustment = rand(-10, 20);
                
                Variation::create([
                    'product_id' => $productId,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'price_adjustment' => $priceAdjustment,
                    'status' => rand(1, 2) // Random active/inactive status
                ]);
            }
        }
    }

    /**
     * Get random category based on gender
     */
    private function getRandomCategory($gender)
    {
        $categories = Category::where(function($query) use ($gender) {
            $query->where('gender', $gender)->orWhere('gender', 'both');
        })->get();

        return $categories->random();
    }

    /**
     * Get random celebrity based on gender
     */
    private function getRandomCelebrity($gender)
    {
        $celebrities = Celebrity::where(function($query) use ($gender) {
            $query->where('gender', $gender)->orWhere('gender', 'both');
        })->get();

        return $celebrities->isNotEmpty() ? $celebrities->random() : null;
    }

    /**
     * Get random brand based on gender
     */
    private function getRandomBrand($gender)
    {
        $brands = Brand::where(function($query) use ($gender) {
            $query->where('gender', $gender)->orWhere('gender', 'both');
        })->get();

        return $brands->isNotEmpty() ? $brands->random() : null;
    }

    /**
     * Get random shop based on gender
     */
    private function getRandomShop($gender)
    {
        $shops = Shop::where(function($query) use ($gender) {
            $query->where('gender', $gender)->orWhere('gender', 'both');
        })->get();

        return $shops->isNotEmpty() ? $shops->random() : null;
    }
}