<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Celebrity;
use App\Models\Service;
use App\Models\Setting;
use App\Models\UserAddress;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CelebrityController extends Controller
{
     use Responses;

     public function index()
     {
         
         $celebrities = Celebrity::get();
         
         return $this->success_response('Celebrity retrieved successfully', $celebrities);
     }
   
     public function getProductsFromCelebrity($id)
     {
         
         $celebrities = Celebrity::with('products','products.images','products.ratings')->where('id',$id)->get();
         
         return $this->success_response('Celebrity retrieved successfully', $celebrities);
     }
   
}
