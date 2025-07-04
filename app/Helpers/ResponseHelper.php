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
                foreach ($data as $tag) {
                    $html .= '<span class="mr-2 p-2 text-white label label-pill tag-labels" style="background-color: '.$tag['tag_color'].' !important;">
                    '.ucfirst(strtolower($tag['tag_name'])).' 
                        <a href="javascript:void(0)" class="text-white" data-trigger="delete-tag" data-id="'.$tag['id'].'">
                            <i class="fa fa-trash"></i></a>
                        </span>';
                }

                $script = "$('.existing-tags').html('".preg_replace('/\s+/', ' ', $html)."'); $('.tag-count').text('".count($data)."'); init_actions();";
                break;
            case 'add-tag':
            case 'delete-tag':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetch_tags();";
                break;
            case 'fetch-extension-settings':
                $script = "$('[data-key=\"ExtensionExpirationDays\"]').val('".$data['extension_expiration_days']."'); 
                $('[data-key=\"ExtensionExpirationHrs\"]').val('".$data['extension_expiration_hrs']."'); 
                $('[data-key=\"IsRandomExtensionGeneration\"]').prop('checked', ".$data['random_extension_generation'].");";
                break;
            case 'generate-extension':
                $script = "$('[data-key=\"extension_number\"]').val('".$data['extension_number']."'); $('[data-key=\"expiration\"]').val('".$data['expiration_date']."');";
                break;
            case 'account-register':
            case 'account-login':
                $script = "window.location.href = '".route('admin.dashboard')."';";
            break;
            case 'account-logout':
                $script = "window.location.href = '".route('login')."';";
            break;
        }

        return ['js' => $script];
    }

    function contactResponse(array $data, string $message='', string $toast_type = 'success', string $title = 'System Info'): array {
        return [];
    }
}