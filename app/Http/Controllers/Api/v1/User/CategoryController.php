<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\Responses;

class CategoryController extends Controller
{
     use Responses;

     public function index()
     {
         
         $categories = Category::get();
         
         return $this->success_response('Category retrieved successfully', $categories);
     }
   
     public function getProductsFromCategory($id)
     {
         
         $categories = Category::with('products','products.images','products.ratings')->where('id',$id)->get();
         
         return $this->success_response('Category retrieved successfully', $categories);
     }
   
}
