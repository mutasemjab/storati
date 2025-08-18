<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Setting;
use App\Models\UserAddress;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class UserAddressController extends Controller
{
    use Responses;

    /**
     * Display a listing of user addresses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id ?? Auth::id();
        
        $addresses = UserAddress::with('delivery')->where('user_id', $user_id)->get();
        
        return $this->success_response('Addresses retrieved successfully', $addresses);
    }

    /**
     * Store a newly created address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'address' => 'required|string',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'delivery_id' => 'required|exists:deliveries,id',
        ]);

        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

        // If user_id is not provided, use authenticated user's ID
        if (!$request->has('user_id')) {
            $request->merge(['user_id' => Auth::id()]);
        }

        $address = UserAddress::create($request->only([
            'user_id', 'address', 'lat', 'lng', 'delivery_id'
        ]));

        return $this->success_response('Address created successfully', $address);
    }

    /**
     * Display the specified address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = UserAddress::with('delivery')->find($id);
        
        if (!$address) {
            return $this->error_response('Address not found', null);
        }

        return $this->success_response('Address retrieved successfully', $address);
    }

    /**
     * Update the specified address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $address = UserAddress::find($id);
        
        if (!$address) {
            return $this->error_response('Address not found', null);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'sometimes|string',
            'lat' => 'sometimes|string',
            'lng' => 'sometimes|string',
            'delivery_id' => 'sometimes|exists:deliveries,id',
        ]);

        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

        $address->update($request->only([
            'address', 'lat', 'lng', 'delivery_id'
        ]));

        return $this->success_response('Address updated successfully', $address);
    }

    /**
     * Remove the specified address from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = UserAddress::find($id);
        
        if (!$address) {
            return $this->error_response('Address not found', null);
        }

        // Check if the authenticated user is authorized to delete this address
        if (Auth::id() != $address->user_id) {
            return $this->error_response('Unauthorized access', null);
        }

        $address->delete();

        return $this->success_response('Address deleted successfully', null);
    }

}