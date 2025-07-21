<?php

declare(strict_types=1);
namespace App\Helpers;

use App\Mail\AxoMailer;
use App\Models\Communication;
use App\Models\Contact;
use App\Models\Extension;
use App\Models\Keyword;
use App\Models\Message;
use App\Models\Otp;
use App\Models\PhoneNumber;
use App\Models\SettingExtension;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use jessedp\Timezones\Timezones;
use OpenAI\Laravel\Facades\OpenAI;

class GlobalHelper {
    
    public function ajaxSuccessResponse(
        string $type="toast", 
        string $toast_type='success',
        string $exec='', 
        string $message='',
        string $title='System Info',
        array $data=[]
    ): JsonResponse {
        
        if ($type == 'toast') {
            $response = responseHelper()->toastrResponse($message, $toast_type, $title);
        } else if ($type == 'scripts') {
            $response = responseHelper()->scriptResponse($exec, $data, $message, $toast_type, $title);
        }
        
        return response()->json($response, 200);
    }

    public function ajaxErrorResponse(string $message='', string $url='', string $title='System Error'): JsonResponse {
        $response = responseHelper()->toastrResponse($message, 'error', $title);
        return response()->json($response, 200);
    }

    public function logInfo(string $message): void {
        Log::channel('info')->info($message);
    }

    public function getTags(): array {
        try {
            $tags = Tag::orderBy('tag_name', 'asc')->get();
            return $tags->toArray();
        } catch (\Exception $e) {
            $this->logInfo("Error fetching tags: " . $e->getMessage());
            return [];
        }
    }

    public function getKeywords() {
        try {
            return array_map(function($item) {
                return strtolower(trim($item));
            }, explode(",", Keyword::get()->pluck('keywords')->toArray()[0]));
        } catch (\Exception $e) {
            $this->logInfo("Error fetching keywords: " . $e->getMessage());
            return [];
        }
    }

    public function getCommunicationData() {
        $communications = Communication::orderBy('date_time', 'desc')->with('transcriptions', 'contact_from', 'contact_to')->get();
        
        return $communications;
    }

    public function getMessageData() {
        $messages = Message::orderBy('date_sent', 'desc')->with('contact_from', 'contact_to')->get();
        
        return $messages;
    }

    public function generateExtension($contact_id = null) {
        try {
            
            
            $setting_extension = SettingExtension::first();
            if (!$setting_extension) {
                return [
                    'extension_number' => '',
                    'expiration_date' => '',
                ];
            }
            
            $extension_expiration_days = $setting_extension->extension_expiration_days;
            $extension_expiration_hrs = $setting_extension->extension_expiration_hrs;
            $random_extension_generation = $setting_extension->random_extension_generation;
            $timezone = $setting_extension->timezone ?? config('app.timezone');

            if ($random_extension_generation) {
                $extension_new = $this->generateRandomExtension();
            } else {
                $extension_new = $this->generateSequentialExtension();
            }
            
            if ($extension_expiration_hrs <= 0) {
                $expiration_date = now()->setTimezone($timezone)->addDays($extension_expiration_days)->format('Y-m-d H:i A');
            } else {
                $expiration_date = now()->setTimezone($timezone)->addDays($extension_expiration_days)->addHours($extension_expiration_hrs)->format('Y-m-d H:i A');
            }
            
            return [
                'extension_number' => $extension_new,
                'expiration_date' => $expiration_date,
            ];
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return [
                'extension_number' => '',
                'expiration_date' => '',
            ];
        }
    }

    private function generateRandomExtension() {
        $extension_new = rand(1000, 9999);
        return $extension_new;
    }

    private function generateSequentialExtension() {
        $last_extension = Extension::orderBy('id', 'desc')->first();
        return $last_extension ? $last_extension->extension_number + 1 : config('custom.extension_start');
    }

    public function getDashboardData($trigger = "dashboard-today", $daterange = null) {
        $settings_keywords = $this->getKeywords();
        
        if ($trigger == "dashboard-today") {
            $keywords_hits = [];
            $keywords_missed = 0;

            $total_communications = Communication::where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();
            $total_messages = Message::where('date_sent', '>=', now()->startOfDay())->where('date_sent', '<=', now()->endOfDay())->count();
            $total_extensions = Extension::where('status', 'active')->where('created_at', '>=', now()->startOfDay())->where('created_at', '<=', now()->endOfDay())->count();
            $total_follow_ups = Communication::where('category', 'follow-up')->where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();
            $total_appointments_booked = Communication::where('is_booked', 'yes')->where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();

            $total_calls_by_hour = Communication::where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->get()->groupBy(function($item) {
                return $item->date_time->format('H');
            })->map(function($item) {
                return $item->count();
            })->toArray();

            $total_calls_with_sentiment = Communication::where('sentiment', '!=', null)->where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();
            $total_positive_calls = Communication::where('sentiment', 'positive')->where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();
            $total_neutral_calls = Communication::where('sentiment', 'neutral')->where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();
            $total_negative_calls = Communication::where('sentiment', 'negative')->where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->count();


            $calls = Communication::where('date_time', '>=', now()->startOfDay())->where('date_time', '<=', now()->endOfDay())->get();

            if ($calls) {
                foreach ($calls as $call) {
                    if ($call->keywords) {
                        $keywords_hit = explode(",", $call->keywords);
                        foreach ($settings_keywords as $setting_keyword) {
                            if (in_array($setting_keyword, $keywords_hit)) {
                                if (isset($keywords_hits[ucfirst(strtolower($setting_keyword))])) {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))]++;
                                } else {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))] = 1;
                                }
                            } else {
                                $keywords_hits[ucfirst(strtolower($setting_keyword))] = 0;
                            }
                        }
                    } else {
                        $keywords_missed++;
                    }
                }
            }
            

        } else if ($trigger == "dashboard-week") {
            $keywords_hits = [];
            $keywords_missed = 0;

            $total_communications = Communication::where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();
            $total_messages = Message::where('date_sent', '>=', now()->startOfWeek())->where('date_sent', '<=', now()->endOfWeek())->count();
            $total_extensions = Extension::where('status', 'active')->where('created_at', '>=', now()->startOfWeek())->where('created_at', '<=', now()->endOfWeek())->count();
            $total_follow_ups = Communication::where('category', 'follow-up')->where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();
            $total_appointments_booked = Communication::where('is_booked', 'yes')->where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();


            $total_calls_by_hour = Communication::where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->get()->groupBy(function($item) {
                return $item->date_time->format('H');
            })->map(function($item) {
                return $item->count();
            })->toArray();

            $total_calls_with_sentiment = Communication::where('sentiment', '!=', null)->where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();
            $total_positive_calls = Communication::where('sentiment', 'positive')->where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();
            $total_neutral_calls = Communication::where('sentiment', 'neutral')->where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();
            $total_negative_calls = Communication::where('sentiment', 'negative')->where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->count();

            $calls = Communication::where('date_time', '>=', now()->startOfWeek())->where('date_time', '<=', now()->endOfWeek())->get();

            if ($calls) {
                foreach ($calls as $call) {
                    if ($call->keywords) {
                        $keywords_hit = explode(",", $call->keywords);
                        foreach ($settings_keywords as $setting_keyword) {
                            if (in_array($setting_keyword, $keywords_hit)) {
                                if (isset($keywords_hits[ucfirst(strtolower($setting_keyword))])) {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))]++;
                                } else {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))] = 1;
                                }
                            } else {
                                $keywords_hits[ucfirst(strtolower($setting_keyword))] = 0;
                            }
                        }
                    } else {
                        $keywords_missed++;
                    }
                }
            }
        }  else if ($trigger == "dashboard-custom") {    
            
            $keywords_hits = [];
            $keywords_missed = 0;
            
            $daterange = explode(" - ", $daterange);
            $start_date = Carbon::parse($daterange[0])->startOfDay();
            $end_date = Carbon::parse($daterange[1])->endOfDay();

            $total_communications = Communication::where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_messages = Message::where('date_sent', '>=', $start_date)->where('date_sent', '<=', $end_date)->count();
            $total_extensions = Extension::where('status', 'active')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->count();
            $total_follow_ups = Communication::where('category', 'follow-up')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_appointments_booked = Communication::where('is_booked', 'yes')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();

            $total_calls_by_hour = Communication::where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->get()->groupBy(function($item) {
                return $item->date_time->format('H');
            })->map(function($item) {
                return $item->count();
            })->toArray();
            
            $total_calls_with_sentiment = Communication::where('sentiment', '!=', null)->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_positive_calls = Communication::where('sentiment', 'positive')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_neutral_calls = Communication::where('sentiment', 'neutral')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_negative_calls = Communication::where('sentiment', 'negative')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();

            $calls = Communication::where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->get();

            if ($calls) {
                foreach ($calls as $call) {
                    if ($call->keywords) {
                        $keywords_hit = explode(",", $call->keywords);
                        foreach ($settings_keywords as $setting_keyword) {
                            if (in_array($setting_keyword, $keywords_hit)) {
                                if (isset($keywords_hits[ucfirst(strtolower($setting_keyword))])) {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))]++;
                                } else {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))] = 1;
                                }
                            } else {
                                $keywords_hits[ucfirst(strtolower($setting_keyword))] = 0;
                            }
                        }
                    } else {
                        $keywords_missed++;
                    }
                }
            }
        }
        
        if ($daterange == "All Time") {
            
            $keywords_hits = [];
            $keywords_missed = 0;
            
            $total_communications = Communication::count();
            $total_messages = Message::count();
            $total_extensions = Extension::where('status', 'active')->count();
            $total_follow_ups = Communication::where('category', 'follow-up')->count();
            $total_appointments_booked = Communication::where('is_booked', 'yes')->count();

            $total_calls_by_hour = Communication::get()->groupBy(function($item) {
                return $item->date_time->format('H');
            })->map(function($item) {
                return $item->count();
            })->toArray();

            $calls = Communication::get();

            if ($calls) {
                foreach ($calls as $call) {
                    if ($call->keywords) {
                        $keywords_hit = explode(",", $call->keywords);
                        foreach ($settings_keywords as $setting_keyword) {
                            if (in_array($setting_keyword, $keywords_hit)) {
                                if (isset($keywords_hits[ucfirst(strtolower($setting_keyword))])) {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))]++;
                                } else {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))] = 1;
                                }
                            } else {
                                $keywords_hits[ucfirst(strtolower($setting_keyword))] = 0;
                            }
                        }
                    } else {
                        $keywords_missed++;
                    }
                }
            }
            
            $total_calls_with_sentiment = Communication::where('sentiment', '!=', null)->count();
            $total_positive_calls = Communication::where('sentiment', 'positive')->count();
            $total_neutral_calls = Communication::where('sentiment', 'neutral')->count();
            $total_negative_calls = Communication::where('sentiment', 'negative')->count();
            
        } else {
            $keywords_hits = [];
            $keywords_missed = 0;
            
            if($daterange) {
                $daterange = explode(" - ", $daterange);
            } else {
                $daterange = [now()->startOfDay(), now()->endOfDay()];
            }
            $start_date = Carbon::parse($daterange[0])->startOfDay();
            $end_date = Carbon::parse($daterange[1])->endOfDay();

            $total_communications = Communication::where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_messages = Message::where('date_sent', '>=', $start_date)->where('date_sent', '<=', $end_date)->count();
            $total_extensions = Extension::where('status', 'active')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->count();
            $total_follow_ups = Communication::where('category', 'follow-up')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_appointments_booked = Communication::where('is_booked', 'yes')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();

            $total_calls_by_hour = Communication::where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->get()->groupBy(function($item) {
                return $item->date_time->format('H');
            })->map(function($item) {
                return $item->count();
            })->toArray();
            
            $total_calls_with_sentiment = Communication::where('sentiment', '!=', null)->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_positive_calls = Communication::where('sentiment', 'positive')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_neutral_calls = Communication::where('sentiment', 'neutral')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();
            $total_negative_calls = Communication::where('sentiment', 'negative')->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->count();

            $calls = Communication::where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date)->get();

            if ($calls) {
                foreach ($calls as $call) {
                    if ($call->keywords) {
                        $keywords_hit = explode(",", $call->keywords);
                        foreach ($settings_keywords as $setting_keyword) {
                            if (in_array($setting_keyword, $keywords_hit)) {
                                if (isset($keywords_hits[ucfirst(strtolower($setting_keyword))])) {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))]++;
                                } else {
                                    $keywords_hits[ucfirst(strtolower($setting_keyword))] = 1;
                                }
                            } else {
                                $keywords_hits[ucfirst(strtolower($setting_keyword))] = 0;
                            }
                        }
                    } else {
                        $keywords_missed++;
                    }
                }
            }
        }

        return [
            'total_communications' => $total_communications,    
            'total_messages' => $total_messages,
            'total_extensions' => $total_extensions,
            'total_follow_ups' => $total_follow_ups,
            'total_appointments_booked' => $total_appointments_booked,
            'total_calls_with_sentiment' => $total_calls_with_sentiment,
            'total_positive_calls' => $total_positive_calls,
            'total_neutral_calls' => $total_neutral_calls,
            'total_negative_calls' => $total_negative_calls,
            'total_calls_by_hour' => $total_calls_by_hour,
            'keywords_hits' => $keywords_hits,
            'keywords_missed' => $keywords_missed,
            'overall_keywords_hit_rate' => array_sum($keywords_hits),
        ];
    }

    public function getProfile() {
        $profile = User::where('id', auth()->user()->id)->with('profile')->first();
        
        return $profile->toArray();
    }

    public function getProfileViaEmail($email) {
        try {
            $user = User::where('email', $email)->first();
            
            return $user->toArray();
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return [];
        }
    }

    public function getEmailDetails($type, $data=[]) {
        
        switch ($type) {
            case 'otp':
                $user_id = $data['id'];
                $otp_data = $this->generateOtp($user_id);
                $data['otp'] = $otp_data['otp'] ?? '';
                $data['verification_url'] = config('app.url') . '/verify-otp?token=' . $otp_data['token'];
                return [
                    'subject' => 'OTP Verification - AxoCall',
                    'view' => 'mail.otp',
                    'data' => $data,
                ];
            default:
                return [
                    'subject' => 'OTP Verification - AxoCall',
                    'view' => 'mail.otp',
                ];
        }
    }

    public function generateOtp($user_id) {

        try {
        $otp = rand(100000, 999999);
        $token = md5(random_bytes(32).$otp);
        $otp_expiration = now()->addMinutes(5);

        $otp_data = [
            'user_id' => $user_id,
            'otp' => $otp,
            'expires_at' => $otp_expiration,
            'type' => 'otp',
            'token' => $token,
        ];

        $otp = Otp::create($otp_data);

        return $otp;
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return [];
        }
    }

    public function sendOtp($data) {
        try {

            $recipient = $data['email'];
            $profile = $this->getProfileViaEmail($recipient);
            $emailDetails = $this->getEmailDetails('otp', $profile);
            
            Mail::to("$recipient")->send(new AxoMailer($emailDetails));

            return [
                'status' => true,
                'message' => 'OTP sent successfully',
                'js' => 'location = "'.$emailDetails['data']['verification_url'].'"',
            ];
        } catch (\Exception $e) {   
            logInfo($e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to send OTP',
            ];
        }
    }


    public function sentimentAnalysis($text) {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a sentiment analysis expert. Analyze the following text and determine the sentiment of the text. The sentiment can be positive, negative, or neutral. Please return the sentiment in a JSON format with the key "sentiment" and the value can be "positive", "negative", or "neutral".'],
                    ['role' => 'user', 'content' => $text],
                ],
            ]);
            
            return response()->json(['status' => true, 'data' => json_decode($response->choices[0]->message->content)]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getTimezones() {
        try {
            $timezones = Timezones::toArray();
            $timezones_list = [];
            foreach ($timezones as $timezone) {
                
                foreach ($timezone as $timezone_key => $timezone_value) {
                    $timezones_list[$timezone_key] = $timezone_key;
                }
            }
            
            return $timezones_list;
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return [];
        }
    }

}