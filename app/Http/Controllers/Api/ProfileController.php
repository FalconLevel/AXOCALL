<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
       
            $validated = validatorHelper()->validate('profile_update', $request);        
            if (! $validated['status']) {
                return response()->json($validated, 400);
            }
            
            $user_data = array_intersect_key(
                $validated['validated'], 
                array_flip(['first_name', 'last_name', 'email', 'phone_number'])
            );
            $profile_data = array_intersect_key(
                $validated['validated'], 
                array_flip(['company', 'street_address', 'apartment', 'city', 'state', 'zip_code', 'country'])
            );

            $user = User::where('id', Auth::user()->id)->update($user_data);
            $profile = Profile::updateOrCreate(['user_id' => Auth::user()->id], $profile_data);
            
            DB::commit();
            
            return [
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user,
                    'profile' => $profile,
                ],
            ];

            
        } catch (Exception $e) {
            logInfo($e->getMessage());
            // DB::rollBack();
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        
    }
}