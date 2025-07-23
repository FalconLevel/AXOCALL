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
            case "edit-user":
                _editUser($(this).data("id"));
                break;
            case "edit-permissions":
                _editPermissions($(this).data("id"));
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

function _editUser(id) {
    console.log(id);
}

function _editPermissions(id) {
    console.log(id);
}

function _resetPassword(id) {
    ajaxRequest("/executor/account/reset-password", { id: id }, "");
}

function _blockUser(id) {
    ajaxRequest("/executor/account/block-user", { id: id }, "");
}

function _activateUser(id) {
    ajaxRequest("/executor/account/activate-user", { id: id }, "");
}
