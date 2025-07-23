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
    ajaxRequest("/executor/account/reset-password", { id: id }, "");
}

function _blockUser(id) {
    ajaxRequest("/executor/account/block-user", { id: id }, "");
}

function _activateUser(id) {
    ajaxRequest("/executor/account/activate-user", { id: id }, "");
}
