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
            case 'toggle-theme':
                $script = "_applyTheme('".$data['theme']."');";
                break;
            case 'users':
                $script = "$('.users-table tbody').html('".preg_replace('/\s+/', ' ', $this->usersResponse($data))."'); _init_actions();";
                break;
            case 'block-user':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetchUsers();";
                break;
            case 'activate-user':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetchUsers();";
                break;
            case 'update-user-role':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetchUsers(); $('#user-role-modal').modal('hide');";
                break;
            case 'delete-role':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetchRoles();";
                break;
            case 'update-role':
            case 'save-role':
                $script = "_show_toastr('".$toast_type."', '".$message."', '".$title."'); _fetchRoles(); $('#role-modal-add').modal('hide');";
                break;
            case 'roles':
                $script = "$('.roles-table tbody').html('".preg_replace('/\s+/', ' ', $this->rolesResponse($data))."'); _init_actions();";
                break;
            case 'edit-role':
                $script = "
                    $('#add_role_name').val('".$data['role']."'); 
                    $('#add_role_description').val('".$data['description']."');
                    $('#role-modal-add input[type=\"checkbox\"][name^=\"permissions\"]').prop('checked', false);
                    ".$this->permissionsResponse($data['permissions'])."
                    $('#update-role-btn').attr('data-id', '".$data['id']."');
                    $('#role-modal-add').modal('show');
                    $('#save-role-btn').hide();
                    $('#update-role-btn').show();";
                break;
            
        }

        return ['js' => $script];
    }

    function contactResponse(array $data, string $message='', string $toast_type = 'success', string $title = 'System Info'): array {
        return [];
    }

    private function usersResponse(array $data): string {
        $html = '';
        if ($data) {
            foreach ($data as $user) {
                $user_status_action = ($user['status'] == 'blocked') ? 
                    '<a href="javascript:void(0)" class="text-success" data-trigger="activate-user" data-id="'.$user['id'].'" title="Activate User">
                            <i class="fa fa-user-check fa-action"></i>
                    </a>': 
                    '<a href="javascript:void(0)" class="text-danger" data-trigger="block-user" data-id="'.$user['id'].'" title="Block User">
                            <i class="fa fa-user-times fa-action"></i>
                    </a>';
                $html .= '<tr>
                    <td>'.ucfirst(strtolower($user['first_name'])).' '.ucfirst(strtolower($user['last_name'])).'</td>
                    <td>'.$user['email'].'</td>
                    <td>'.($user['role'] ? ucfirst(strtolower($user['role']['role'])) : '-').'</td>
                    <td>'.($user['status'] == 'active' ? 'Active' : ($user['status'] == 'blocked' ? 'Blocked' : 'Inactive')).'</td>
                    <td>
                        
                        <a href="javascript:void(0)" class="text-success mr-2" data-trigger="edit-user-role" data-id="'.$user['id'].'" title="Edit Role">
                            <i class="fa fa-user-tag fa-action"></i>
                        </a>
                        <a href="javascript:void(0)" class="text-primary mr-2" data-trigger="reset-password" data-id="'.$user['id'].'" title="Reset Password">
                            <i class="fa fa-key fa-action"></i>
                        </a>
                        '.$user_status_action.'
                    </td>
                </tr>';
            }
        }
        return $html;
    }

    private function rolesResponse(array $data): string {
        $html = '';
        if ($data) {
            foreach ($data as $role) {
                $html .= '<tr>
                    <td>'.ucfirst(strtolower($role['role'])).'</td>
                    <td>'.$role['description'].'</td>
                    <td>
                        <a 
                            href="javascript:void(0)" 
                            class="text-warning mr-2" 
                            data-trigger="edit-role" 
                            data-id="'.$role['id'].'" 
                            title="Edit Role"
                        >
                            <i class="fa fa-edit fa-action"></i>
                        </a>
                        <a href="javascript:void(0)" class="text-danger mr-2" data-trigger="delete-role" data-id="'.$role['id'].'" title="Delete Role">
                            <i class="fa fa-trash fa-action"></i>
                        </a>
                    </td>
                </tr>';
            }
        }
        return $html;
    }

    private function permissionsResponse(string $data): string {
        $permissions = json_decode($data, true);
        $scripts = '';
        if ($permissions) {
            foreach ($permissions as $menu => $permission) {
                foreach ($permission as $action => $value) { 
                $scripts .= "$(
                        `#role-modal-add input[type=\"checkbox\"][name=\"permissions[$menu][$action]\"]`
                    ).prop(\"checked\", $value == 1);";
                }
            }
        }
        return $scripts;
    }
}