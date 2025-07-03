<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactTag;
use App\Models\PhoneNumber;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function all(): JsonResponse {
        try {
            $contacts = Contact::with('phoneNumbers', 'tags')->get()->toArray();

            if ($contacts) {
                $contacts = array_map(function ($contact) {
                    $contact['first_name'] = ucfirst(strtolower($contact['first_name']));
                    return $contact;
                }, $contacts);
            }
            return response()->json(['status' => 'success', 'data' => $contacts]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function save(Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $tags = $request->Tags ?? [];
            $phones = $request->PhoneNumbers ?? [];
            
            $validated = validatorHelper()->validate('contact_save', $request);
            
            if (! $validated['status']) {
                return response()->json($validated, 400);
            }

            if (!$phones) {
                return response()->json(['status' => 'error', 'message' => 'Atleast one phone number is required'], 400);
            }

            foreach ($phones as $phone) {
                if (!$phone['phone_number']) {
                    return response()->json(['status' => 'error', 'message' => 'Phone number is required'], 400);
                }
            }

            $contact = Contact::create($validated['validated']);
            $contact_id = $contact->id;
            
            $phone_number_data = array_map(function ($phone) use ($contact_id) {
                return [
                    'contact_id' => $contact_id,
                    'phone_number' => formatHelper()->formatPhoneNumberWithParenthesis($phone['phone_number']),
                    'phone_number_formatted' => formatHelper()->formatPhoneNumber($phone['phone_number']),
                    'phone_ext' => $phone['phone_ext'] ?? null,
                    'phone_type' => $phone['phone_type'] ?? null,
                ];
            }, $phones);

            PhoneNumber::insert($phone_number_data);
            
            if ($tags) {
                $tag_data = array_map(function ($tag) use ($contact_id) {
                    return [
                        'contact_id' => $contact_id,
                        'tag_id' => $tag ?? null,
                    ];
                }, $tags);

                ContactTag::insert($tag_data);
            }
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Contact saved successfully']);

        } catch(QueryException $e) {
            DB::rollBack();
            logInfo($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Duplicate phone number'], 400);
        }
        catch (\Exception $e) {
            DB::rollBack();
            logInfo(json_encode($e->getTrace()));
            return response()->json(['status' => 'error', 'message' => 'Please call system administrator'], 500);
        }
    }

    public function edit($id): JsonResponse {
        $contact = Contact::with('phoneNumbers', 'tags')->find($id);
        
        if ($contact) {
            $contact = $contact->toArray();

            $contact['phone_numbers'] = array_map(function ($phone) {
                $phone['phone_number'] = formatHelper()->unFormatPhoneNumber($phone['phone_number']);
                return $phone;
            }, $contact['phone_numbers']);
        }
        return response()->json(['status' => 'success', 'data' => $contact]);
    }

    public function update(Request $request, $id): JsonResponse {
        $contact = Contact::with('phoneNumbers', 'tags')->find($id);
        
        $validated = validatorHelper()->validate('contact_save', $request);
        
        if (! $validated['status']) {
            return response()->json($validated, 400);
        }

        $contact->update($validated['validated']);

        $phones = $request->PhoneNumbers ?? [];
        $tags = $request->Tags ?? [];

        $contact->phoneNumbers()->delete();
        $contact->tags()->delete(); 

        if ($phones) {
            $phone_number_data = array_map(function ($phone) use ($contact) {
                return [
                    'contact_id' => $contact->id,
                    'phone_number' => formatHelper()->formatPhoneNumberWithParenthesis($phone['phone_number']),
                    'phone_number_formatted' => formatHelper()->formatPhoneNumber($phone['phone_number']),
                    'phone_ext' => $phone['phone_ext'] ?? null,
                    'phone_type' => $phone['phone_type'] ?? null,
                ];
            }, $phones);

            PhoneNumber::insert($phone_number_data);
        }

        if ($tags) {
            $tag_data = array_map(function ($tag) use ($contact) {  
                return [
                    'contact_id' => $contact->id,
                    'tag_id' => $tag ?? null,
                ];
            }, $tags);

            ContactTag::insert($tag_data);
        }

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Contact updated successfully']);
    }

    public function delete($id): JsonResponse {
        try {
            DB::beginTransaction();
            
            $contact = Contact::with('phoneNumbers', 'tags')->find($id);
            $contact->delete();
            $contact->phoneNumbers()->delete();
            $contact->tags()->delete(); 

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Contact deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logInfo($e->getMessage());  
            return response()->json(['status' => 'error', 'message' => 'Please call system administrator'], 500);
        }
    }

    public function phoneNumbers($id): JsonResponse {
        try {
            $phoneNumbers = PhoneNumber::where('contact_id', $id)->get()->toArray();
            return response()->json(['status' => 'success', 'data' => $phoneNumbers]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Please call system administrator'], 500);
        }
    }

    public function view($id): JsonResponse {
        $contact = Contact::with('phoneNumbers', 'tags')->find($id);
        return response()->json(['status' => 'success', 'data' => $contact]);
    }
}