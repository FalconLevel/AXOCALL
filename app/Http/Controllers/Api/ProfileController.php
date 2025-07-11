<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
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

            
            $profile_data['user_id'] = auth()->user()->id;
            $user = User::where('id', auth()->user()->id)->update($user_data);
            $profile = Profile::updateOrCreate(['user_id' => auth()->user()->id], $profile_data);
            
            return [
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user,
                    'profile' => $profile,
                ],
            ];

            DB::commit();
            return response()->json($profile, 200);
        } catch (\Throwable $th) {
            logInfo($th->getMessage());
            DB::rollBack();
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
        
    }
}