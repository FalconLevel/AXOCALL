$(document).ready(function () {
    _fetchUsers();

    $(".users-table").DataTable();
});

function _fetchUsers() {
    ajaxRequest("/executor/account/get-users", {}, "users");
}

function _init_actions() {
    $("[data-trigger").off();
    $("[data-trigger").on("click", function () {
        let trigger = $(this).data("trigger");
        switch (trigger) {
            case "add-role":
                location = $(this).attr("data-url");
                break;
            case "edit-user-role":
                _editUserRole($(this).data("id"));
                break;
            case "reset-password":
                _resetPassword($(this).data("id"));
                break;
            case "block-user":
                _blockUser($(this).data("id"));
                break;
            case "activate-user":
                _activateUser($(this).data("id"));
                break;
        }
    });
}

function _resetPassword(id) {
    _confirm(
        "Are you sure you want to reset this user's password?",
        "A new password will be generated and sent to the user's email address.",
        "warning",
        "Yes, reset password",
        true,
        function () {
            ajaxRequest("/executor/account/reset-password", { id: id }, "");
        }
    );
}

function _blockUser(id) {
    _confirm(
        "Are you sure you want to block this user?",
        "The user will not be able to access the system until they are activated again.",
        "warning",
        "Yes, block user",
        true,
        function () {
            ajaxRequest("/executor/account/block-user", { id: id }, "");
        }
    );
}

function _activateUser(id) {
    _confirm(
        "Are you sure you want to activate this user?",
        "The user will be able to access the system again.",
        "info",
        "Yes, activate user",
        true,
        function () {
            ajaxRequest("/executor/account/activate-user", { id: id }, "");
        }
    );
}

function _editUserRole(userId) {
    $("#edit_user_id").val(userId);
    $("#edit_role_id").val("");

    $("#user-role-modal").modal("show");
}

function _updateUserRole() {
    const userId = $("#edit_user_id").val();
    const roleId = $("#edit_role_id").val();

    if (!roleId) {
        _show_toastr("error", "Please select a role", "System Error");
        return;
    }

    ajaxRequest(
        "/executor/account/update-user-role",
        {
            user_id: userId,
            role_id: roleId,
        },
        "update-user-role",
        function (data) {
            $("#editUserRoleModal").modal("hide");
            _fetchUsers(); // Refresh the users table
        }
    );
}
