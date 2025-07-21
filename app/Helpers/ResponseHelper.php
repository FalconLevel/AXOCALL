<?php

declare(strict_types=1);
namespace App\Helpers;

class ResponseHelper {
    public function toastrResponse(string $message, string $type = 'error', string $title = 'System Error'): array {
        return [
            'js' => "_show_toastr('".$type."', '".$message."', '".$title."');"
        ];
    }

    public function scriptResponse(string $exec, array $data, string $message='', string $toast_type = 'success', string $title = 'System Info'): array {
        $script = '';
        switch ($exec) {    
            case 'fetch-tag':
                $html = '';
                if ($data) {
                    foreach ($data as $tag) {
                        $html .= '<span class="mr-2 p-2 text-white label label-pill tag-labels" style="background-color: '.$tag['tag_color'].' !important;">
                        '.ucfirst(strtolower($tag['tag_name'])).' 
                            <a href="javascript:void(0)" class="text-white" data-trigger="delete-tag" data-id="'.$tag['id'].'">
                                <i class="fa fa-trash"></i></a>
                            </span>';
                    }
                }

                $script = "$('.existing-tags').html('".preg_replace('/\s+/', ' ', $html)."'); $('.tag-count').text('".count($data)."'); init_actions();";
                break;
            case 'add-tag':
            case 'delete-tag':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetch_tags();";
                break;
            case 'fetch-extension-settings':
                if ($data) {
                $script = "$('[data-key=\"ExtensionExpirationDays\"]').val('".$data['extension_expiration_days']."'); 
                    $('[data-key=\"ExtensionExpirationHrs\"]').val('".$data['extension_expiration_hrs']."'); 
                    $('[data-key=\"RandomExtensionGeneration\"]').prop('checked', ".($data['random_extension_generation'] == 1 ? true : false).");
                    $('[data-key=\"Timezone\"]').val('".$data['timezone']."');";

                }
                break;
            case 'generate-extension':
                $script = "
                    $('[data-key=\"extension_number\"]').val('".$data['extension_number']."'); 
                    $('[data-key=\"expiration\"]').val('".$data['expiration_date']."');";
                break;
            case 'account-register':
                $script = $data['js'];
                break;
            case 'account-login':
                $script = "window.location.href = '".route('admin.dashboard')."';";
            break;
            case 'account-logout':
                $script = "window.location.href = '".route('login')."';";
            break;
            case 'save-keywords':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."');";
                break;
            case 'fetch-keywords':
                $script = isset($data['keywords']) ? "$('[data-key=\"Keywords\"]').val('".strtolower($data['keywords'])."');" : "$('[data-key=\"Keywords\"]').val('');";
                break;
            case 'fetch-email-settings':
                $script = isset($data['email_addresses']) ? "$('[data-key=\"EmailAddresses\"]').val('".strtolower($data['email_addresses'])."');" : "$('[data-key=\"EmailAddresses\"]').val('');";
                $script .= isset($data['frequency']) ? "$('[data-key=\"Frequency\"]').val('".strtolower($data['frequency'])."');" : "$('[data-key=\"Frequency\"]').val('');";
                $script .= isset($data['day_of_week']) ? "$('[data-key=\"DayOfWeek\"]').val('".strtolower($data['day_of_week'])."');" : "$('[data-key=\"DayOfWeek\"]').val('');";
                $script .= isset($data['is_enabled']) ? "$('[data-key=\"IsEmailSummaries\"]').prop('checked', ".($data['is_enabled'] == 1 ? true : false).");" : "$('[data-key=\"IsEmailSummaries\"]').prop('checked', false);";
                if (isset($data['frequency']) && $data['frequency'] == 'weekly') {
                    $script .= "$('#day-of-week-container').removeClass('d-none');";
                } else {
                    $script .= "$('#day-of-week-container').removeClass('d-none').addClass('d-none');";
                }
                break;
            case 'fetch-smart-callback':
                $script = isset($data['hours']) ? "$('[data-key=\"SmartCallbackHours\"]').val('".$data['hours']."');" : "$('[data-key=\"SmartCallbackHours\"]').val('');";
                $script .= isset($data['minutes']) ? "$('[data-key=\"SmartCallbackMinutes\"]').val('".$data['minutes']."');" : "$('[data-key=\"SmartCallbackMinutes\"]').val('');";
                $script .= isset($data['is_active']) ? "$('[data-key=\"SmartCallbackIsActive\"]').prop('checked', ".($data['is_active'] == 1 ? true : false).");" : "$('[data-key=\"SmartCallbackIsActive\"]').prop('checked', false);";
                break;
        }

        return ['js' => $script];
    }

    function contactResponse(array $data, string $message='', string $toast_type = 'success', string $title = 'System Info'): array {
        return [];
    }
}