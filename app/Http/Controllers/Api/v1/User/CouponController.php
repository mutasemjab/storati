<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\Responses;
use Illuminate\Http\Request;
use App\Models\UserCoupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    use Responses;

    /**
     * Show all available (non-expired) coupons.
     */
    public function index()
    {
        $coupons = Coupon::whereDate('expired_at', '>=', now())->get();

        return $this->success_response('Available coupons', $coupons);
    }

 
}

