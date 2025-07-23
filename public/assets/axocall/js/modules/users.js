$(document).ready(function () {
    _fetchUsers();
});

function _fetchUsers() {
    ajaxRequest("/executor/account/get-users", {}, "users");
}
