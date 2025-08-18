<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;
use App\Traits\Responses;


class ShopController extends Controller
{
     use Responses;

     public function index()
     {
         
         $shops = Shop::get();
         
         return $this->success_response('Shop retrieved successfully', $shops);
     }
   
     public function getProductsFromShop($id)
     {
         
         $shops = Shop::with('products','products.images','products.ratings')->where('id',$id)->get();
         
         return $this->success_response('Shop retrieved successfully', $shops);
     }
   
}
