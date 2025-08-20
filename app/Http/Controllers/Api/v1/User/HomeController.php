<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Celebrity;
use App\Models\CelebrityStory;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Shop;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use Responses;

    /**
     * Get complete home data for mobile app (stories, my_collabs products, banners, shops)
     */
    public function getHomeData(Request $request)
    {
        try {
            // Get language preference from request (default to 'en')
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $homeData = [
                'stories' => $this->getStoriesData($locale),
                'my_collabs_products' => $this->getMyCollabsProductsData($locale),
                'banners' => $this->getBannersData(),
                'shops' => $this->getShopsData($locale),
                'featured_products' => $this->getFeaturedProductsData($locale),
            ];

            return $this->success_response(
                __('messages.Home data retrieved successfully'),
                $homeData
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching home data'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Get all active celebrity stories for mobile app
     */
    public function getStories(Request $request)
    {
        try {
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $storiesData = $this->getStoriesData($locale);

            return $this->success_response(
                __('messages.Stories retrieved successfully'),
                $storiesData
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching stories'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Get My Collabs Products (my_collabs = 1)
     */
    public function getMyCollabsProducts(Request $request)
    {
        try {
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $productsData = $this->getMyCollabsProductsData($locale);

            return $this->success_response(
                __('messages.My Collabs products retrieved successfully'),
                $productsData
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching My Collabs products'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Get Banners
     */
    public function getBanners(Request $request)
    {
        try {
            $bannersData = $this->getBannersData();

            return $this->success_response(
                __('messages.Banners retrieved successfully'),
                $bannersData
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching banners'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Get Shops
     */
    public function getShops(Request $request)
    {
        try {
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $shopsData = $this->getShopsData($locale);

            return $this->success_response(
                __('messages.Shops retrieved successfully'),
                $shopsData
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching shops'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Get stories for a specific celebrity
     */
    public function getCelebrityStories(Request $request, $celebrityId)
    {
        try {
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $celebrity = Celebrity::with(['activeStories' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])->find($celebrityId);

            if (!$celebrity) {
                return $this->error_response(
                    __('messages.Celebrity not found'),
                    []
                );
            }

            $stories = [];
            foreach ($celebrity->activeStories as $story) {
                $stories[] = [
                    'id' => $story->id,
                    'type' => $story->type,
                    'media_url' => $story->media_url,
                    'thumbnail_url' => $story->thumbnail_url,
                    'caption' => $story->caption,
                    'views_count' => $story->views_count,
                    'duration' => $story->type === 'photo' ? 5 : null,
                    'created_at' => $story->created_at->toISOString(),
                    'expires_at' => $story->expires_at ? $story->expires_at->toISOString() : null,
                    'time_ago' => $story->created_at->diffForHumans(),
                ];
            }

            $data = [
                'celebrity' => [
                    'id' => $celebrity->id,
                    'name' => $locale === 'ar' ? $celebrity->name_ar : $celebrity->name_en,
                    'name_en' => $celebrity->name_en,
                    'name_ar' => $celebrity->name_ar,
                    'photo' => asset('storage/' . $celebrity->photo),
                    'stories_count' => count($stories),
                ],
                'stories' => $stories
            ];

            return $this->success_response(
                __('messages.Celebrity stories retrieved successfully'),
                $data
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching celebrity stories'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Mark story as viewed (increment view count) and return story details
     */
     public function viewStory(Request $request, $storyId)
    {
        try {
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $story = CelebrityStory::with('celebrity')->find($storyId);

            if (!$story) {
                return $this->error_response(
                    __('messages.Story not found'),
                    []
                );
            }

            // Check if story is still active and not expired
            if (!$story->is_active || $story->isExpired()) {
                return $this->error_response(
                    __('messages.Story is no longer available'),
                    []
                );
            }

            // Get authenticated user using your custom guard
            $token = $request->bearerToken();
            $authenticatedUser = null;
            
            if ($token) {
                try {
                    $authenticatedUser = Auth::guard('user-api')->user();
                } catch (\Exception $e) {
                    return $this->error_response(
                        __('messages.Authentication required'),
                        ['error' => 'Invalid token']
                    );
                }
            }

            if (!$authenticatedUser) {
                return $this->error_response(
                    __('messages.Authentication required'),
                    []
                );
            }

            // Mark story as viewed by this user (only once per user per story)
            $storyView = \App\Models\StoryView::markAsViewed($authenticatedUser->id, $story->id);
            
            // Increment total views count only if this is a new view
            if ($storyView->wasRecentlyCreated) {
                $story->increment('views_count');
                $story->refresh(); // Refresh to get updated views_count
            }

            // Return complete story details
            $data = [
                'id' => $story->id,
                'type' => $story->type,
                'media_url' => $story->media_url,
                'thumbnail_url' => $story->thumbnail_url,
                'caption' => $story->caption,
                'views_count' => $story->views_count,
                'duration' => $story->type === 'photo' ? 5 : null,
                'created_at' => $story->created_at->toISOString(),
                'expires_at' => $story->expires_at ? $story->expires_at->toISOString() : null,
                'time_ago' => $story->created_at->diffForHumans(),
                'is_viewed_by_user' => true, // Now it's definitely viewed
                'celebrity' => [
                    'id' => $story->celebrity->id,
                    'name' => $locale === 'ar' ? $story->celebrity->name_ar : $story->celebrity->name_en,
                    'name_en' => $story->celebrity->name_en,
                    'name_ar' => $story->celebrity->name_ar,
                    'photo' => asset('storage/' . $story->celebrity->photo),
                ]
            ];

            return $this->success_response(
                __('messages.Story viewed successfully'),
                $data
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while viewing story'),
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Get story details by ID
     */
    public function getStoryDetails(Request $request, $storyId)
    {
        try {
            $locale = $request->header('Accept-Language', 'en');
            app()->setLocale($locale);

            $story = CelebrityStory::with('celebrity')->find($storyId);

            if (!$story) {
                return $this->error_response(
                    __('messages.Story not found'),
                    []
                );
            }

            if (!$story->is_active || $story->isExpired()) {
                return $this->error_response(
                    __('messages.Story is no longer available'),
                    []
                );
            }

            $data = [
                'id' => $story->id,
                'type' => $story->type,
                'media_url' => $story->media_url,
                'thumbnail_url' => $story->thumbnail_url,
                'caption' => $story->caption,
                'views_count' => $story->views_count,
                'duration' => $story->type === 'photo' ? 5 : null,
                'created_at' => $story->created_at->toISOString(),
                'expires_at' => $story->expires_at ? $story->expires_at->toISOString() : null,
                'time_ago' => $story->created_at->diffForHumans(),
                'celebrity' => [
                    'id' => $story->celebrity->id,
                    'name' => $locale === 'ar' ? $story->celebrity->name_ar : $story->celebrity->name_en,
                    'name_en' => $story->celebrity->name_en,
                    'name_ar' => $story->celebrity->name_ar,
                    'photo' => asset('storage/' . $story->celebrity->photo),
                ]
            ];

            return $this->success_response(
                __('messages.Story details retrieved successfully'),
                $data
            );

        } catch (\Exception $e) {
            return $this->error_response(
                __('messages.Error occurred while fetching story details'),
                ['error' => $e->getMessage()]
            );
        }
    }

    // ===================== PRIVATE HELPER METHODS =====================

    /**
     * Get stories data with user view tracking (works with or without authentication)
     */
    private function getStoriesData($locale, $request = null)
    {
        // Get authenticated user using your custom guard
        $authenticatedUser = null;
        
        // Try to get authenticated user from the user-api guard
        try {
            if (auth()->guard('user-api')->check()) {
                $authenticatedUser = auth()->guard('user-api')->user();
            }
        } catch (\Exception $e) {
            // If authentication fails, continue as guest
            $authenticatedUser = null;
        }
        
        // Also try to get from request bearer token if guard check fails
        if (!$authenticatedUser && $request) {
            $token = $request->bearerToken();
            if ($token) {
                try {
                    // Set the token and attempt to get user
                    auth()->guard('user-api')->setToken($token);
                    $authenticatedUser = auth()->guard('user-api')->user();
                } catch (\Exception $e) {
                    // Token is invalid or expired, continue as guest
                    $authenticatedUser = null;
                }
            }
        }
        
        $userId = $authenticatedUser ? $authenticatedUser->id : null;

        // Get celebrities that have active stories
        $celebrities = Celebrity::with(['activeStories' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->whereHas('activeStories')
        ->orderBy('name_en')
        ->get();

        $storiesData = [];

        foreach ($celebrities as $celebrity) {
            $stories = [];
            $hasUnseenStories = false;
            
            foreach ($celebrity->activeStories as $story) {
                $isViewedByUser = $userId ? $story->isViewedByUser($userId) : false;
                
                // If user hasn't viewed this story, mark celebrity as having unseen stories
                // For guest users (no userId), all stories are considered unseen
                if (!$isViewedByUser) {
                    $hasUnseenStories = true;
                }

                $stories[] = [
                    'id' => $story->id,
                    'type' => $story->type,
                    'media_url' => $story->media_url,
                    'thumbnail_url' => $story->thumbnail_url,
                    'caption' => $story->caption,
                    'views_count' => $story->views_count,
                    'duration' => $story->type === 'photo' ? 5 : null,
                    'created_at' => $story->created_at->toISOString(),
                    'expires_at' => $story->expires_at ? $story->expires_at->toISOString() : null,
                    'time_ago' => $story->created_at->diffForHumans(),
                    'is_viewed_by_user' => $isViewedByUser,
                ];
            }

            if (!empty($stories)) {
                $storiesData[] = [
                    'celebrity' => [
                        'id' => $celebrity->id,
                        'name' => $locale === 'ar' ? $celebrity->name_ar : $celebrity->name_en,
                        'name_en' => $celebrity->name_en,
                        'name_ar' => $celebrity->name_ar,
                        'photo' => asset('storage/' . $celebrity->photo),
                        'stories_count' => count($stories),
                        'has_unseen_stories' => $hasUnseenStories, // RED ring if true, GRAY if false
                        'user_authenticated' => !is_null($userId), // For debugging purposes
                    ],
                    'stories' => $stories
                ];
            }
        }

        return $storiesData;
    }


    /**
     * Get My Collabs products data (my_collabs = 1)
     */
    private function getMyCollabsProductsData($locale)
    {
        $products = Product::with(['celebrity', 'brand', 'shop', 'category'])
            ->where('my_collabs', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $productsData = [];

        foreach ($products as $product) {
            $productsData[] = [
                'id' => $product->id,
                'name' => $locale === 'ar' ? $product->name_ar : $product->name_en,
                'name_en' => $product->name_en,
                'name_ar' => $product->name_ar,
                'description' => $locale === 'ar' ? $product->description_ar : $product->description_en,
                'description_en' => $product->description_en,
                'description_ar' => $product->description_ar,
                'price' => $product->price,
                'tax' => $product->tax,
                'discount_percentage' => $product->discount_percentage,
                'price_after_discount' => $product->price_after_discount,
                'final_price' => $product->price_after_discount ?: $product->price,
                'is_discounted' => !is_null($product->discount_percentage) && $product->discount_percentage > 0,
                'is_featured' => $product->is_featured == 1,
                'celebrity' => $product->celebrity ? [
                    'id' => $product->celebrity->id,
                    'name' => $locale === 'ar' ? $product->celebrity->name_ar : $product->celebrity->name_en,
                    'photo' => asset('storage/' . $product->celebrity->photo),
                ] : null,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $locale === 'ar' ? $product->brand->name_ar : $product->brand->name_en,
                ] : null,
                'shop' => $product->shop ? [
                    'id' => $product->shop->id,
                    'name' => $locale === 'ar' ? $product->shop->name_ar : $product->shop->name_en,
                    'photo' => asset('assets/admin/uploads/' . $product->shop->photo),
                ] : null,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $locale === 'ar' ? $product->category->name_ar : $product->category->name_en,
                ] : null,
                'created_at' => $product->created_at->toISOString(),
            ];
        }

        return $productsData;
    }
 
 
    private function getFeaturedProductsData($locale)
    {
        $products = Product::with(['celebrity', 'brand', 'shop', 'category'])
            ->where('is_featured', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $productsData = [];

        foreach ($products as $product) {
            $productsData[] = [
                'id' => $product->id,
                'name' => $locale === 'ar' ? $product->name_ar : $product->name_en,
                'name_en' => $product->name_en,
                'name_ar' => $product->name_ar,
                'description' => $locale === 'ar' ? $product->description_ar : $product->description_en,
                'description_en' => $product->description_en,
                'description_ar' => $product->description_ar,
                'price' => $product->price,
                'tax' => $product->tax,
                'discount_percentage' => $product->discount_percentage,
                'price_after_discount' => $product->price_after_discount,
                'final_price' => $product->price_after_discount ?: $product->price,
                'is_discounted' => !is_null($product->discount_percentage) && $product->discount_percentage > 0,
                'is_featured' => $product->is_featured == 1,
                'celebrity' => $product->celebrity ? [
                    'id' => $product->celebrity->id,
                    'name' => $locale === 'ar' ? $product->celebrity->name_ar : $product->celebrity->name_en,
                    'photo' => asset('storage/' . $product->celebrity->photo),
                ] : null,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $locale === 'ar' ? $product->brand->name_ar : $product->brand->name_en,
                ] : null,
                'shop' => $product->shop ? [
                    'id' => $product->shop->id,
                    'name' => $locale === 'ar' ? $product->shop->name_ar : $product->shop->name_en,
                    'photo' => asset('assets/admin/uploads/' . $product->shop->photo),
                ] : null,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $locale === 'ar' ? $product->category->name_ar : $product->category->name_en,
                ] : null,
                'created_at' => $product->created_at->toISOString(),
            ];
        }

        return $productsData;
    }

    /**
     * Get banners data
     */
    private function getBannersData()
    {
        $banners = Banner::with('product')->orderBy('created_at', 'desc')->get();

        $bannersData = [];

        foreach ($banners as $banner) {
            $bannersData[] = [
                'id' => $banner->id,
                'photo' => asset('storage/' . $banner->photo),
                'product' => $banner->product ? [
                    'id' => $banner->product->id,
                    'name_en' => $banner->product->name_en,
                    'name_ar' => $banner->product->name_ar,
                    'price' => $banner->product->price,
                    'price_after_discount' => $banner->product->price_after_discount,
                ] : null,
                'created_at' => $banner->created_at->toISOString(),
            ];
        }

        return $bannersData;
    }

    /**
     * Get shops data
     */
    private function getShopsData($locale)
    {
        $shops = Shop::orderBy('name_en')->get();

        $shopsData = [];

        foreach ($shops as $shop) {
            $shopsData[] = [
                'id' => $shop->id,
                'name' => $locale === 'ar' ? $shop->name_ar : $shop->name_en,
                'name_en' => $shop->name_en,
                'name_ar' => $shop->name_ar,
                'photo' => asset('assets/admin/uploads/' . $shop->photo),
                'products_count' => $shop->products()->count(),
                'created_at' => $shop->created_at->toISOString(),
            ];
        }

        return $shopsData;
    }
}