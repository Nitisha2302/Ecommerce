<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'device_id'    => 'required|string|max:255',
            'device_type'  => 'required|string|in:android,ios',
            'fcm_token'    => 'required|string|max:255',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
        ], [
            // ✅ Custom Error Messages
            'first_name.required'   => 'First name is required.',
            'last_name.required'    => 'Last name is required.',
            'email.required'        => 'Email is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.unique'   => 'This phone number is already registered.',
            'device_id.required'    => 'Device ID is required.',
            'device_type.required'  => 'Device type is required.',
            'device_type.in'        => 'Device type must be either android or ios.',
            'fcm_token.required'    => 'FCM token is required.',
            'latitude.required'     => 'Latitude is required.',
            'latitude.numeric'      => 'Latitude must be a valid number.',
            'longitude.required'    => 'Longitude is required.',
            'longitude.numeric'     => 'Longitude must be a valid number.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // return first error
            ], 422);
        }

        $apiToken = bin2hex(random_bytes(30));

        $user = User::create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'device_id'    => $request->device_id,
            'device_type'  => $request->device_type,
            'fcm_token'    => $request->fcm_token,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'api_token'    => $apiToken,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully.',
            'data'    => $user,
        ], 201);
    }


   public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.exists'   => 'This phone number is not registered.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Generate new API token
        $apiToken = bin2hex(random_bytes(30));
        $user->api_token = $apiToken;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Login successful.',
            'data'    => [
                'id'             => $user->id,
                'api_token'      => $user->api_token,
                'first_name'     => $user->first_name,
                'last_name'      => $user->last_name,
                'phone_number'   => $user->phone_number,
                'email'          => $user->email,
                'is_social'      => $user->is_social,
                'device_type'    => $user->device_type,
                'device_id'      => $user->device_id,
                'fcm_token'      => $user->fcm_token,
                'latitude'       => $user->latitude,
                'longitude'      => $user->longitude,
                'created_at'     => $user->created_at,
                'updated_at'     => $user->updated_at,
            ],
        ], 200);
    }



    public function getProfile(Request $request)
    {
        // Fetch the authenticated user
        $user = Auth::guard('api')->user();


        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User profile fetched successfully.',
            'data'    => [
                'id'           => $user->id,
                'first_name'   => $user->first_name,
                'last_name'    => $user->last_name,
                'phone_number' => $user->phone_number,
                'email'        => $user->email,
                'is_social'    => $user->is_social,
                'device_type'  => $user->device_type,
                'device_id'    => $user->device_id,
                'fcm_token'    => $user->fcm_token,
                'latitude'     => $user->latitude,
                'longitude'    => $user->longitude,
                'created_at'   => $user->created_at,
                'updated_at'   => $user->updated_at,
            ]
        ], 200);
    }


    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        // Clear tokens and device info
        $user->api_token       = null;
        $user->google_token    = null;
        $user->facebook_token  = null;
        $user->apple_token     = null;
        $user->device_id      = null;
        $user->device_type     = null;
        $user->is_social       = 0;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully.',
        ], 200);
    }



    





}
