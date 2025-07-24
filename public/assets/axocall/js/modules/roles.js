$(document).ready(function () {
    _fetchRoles();
    $(".roles-table").DataTable();

    $("[data-trigger]").off();
    $("[data-trigger]").on("click", function () {
        var trigger = $(this).data("trigger");
        var url = $(this).data("url");

        switch (trigger) {
            case "add-role":
                // Clear modal values
                $("#add_role_name").val("");
                $("#add_role_description").val("");
                $("#save-role-btn").show();
                $("#update-role-btn").hide();
                $(
                    '#role-modal-add input[type="checkbox"][name^="permissions"]'
                ).prop("checked", false);
                $("#role-modal-add").modal("show");
                break;
            case "save-role":
                // Collect permissions in JSON format
                var permissions = {};
                $(
                    '#role-modal-add input[type="checkbox"][name^="permissions"]'
                ).each(function () {
                    var name = $(this).attr("name"); // e.g. permissions[dashboard][read]
                    var checked = $(this).is(":checked");
                    // Extract keys using regex
                    var match = name.match(
                        /^permissions\[([^\]]+)\]\[([^\]]+)\]$/
                    );
                    if (match) {
                        var menu = match[1];
                        var action = match[2];
                        if (!permissions[menu]) {
                            permissions[menu] = {};
                        }
                        permissions[menu][action] = checked ? 1 : 0;
                    }
                });

                if ($("#add_role_name").val() == "") {
                    _show_toastr(
                        "error",
                        "Role name is required",
                        "User Error"
                    );
                    return;
                }
                var data = {
                    Role: $("#add_role_name").val(),
                    Description: $("#add_role_description").val(),
                    Permissions: JSON.stringify(permissions),
                };

                ajaxRequest("/executor/account/save-role", data, "");

                // permissions is now a JSON object
                break;
            case "update-role":
                var permissions = {};
                $(
                    '#role-modal-add input[type="checkbox"][name^="permissions"]'
                ).each(function () {
                    var name = $(this).attr("name"); // e.g. permissions[dashboard][read]
                    var checked = $(this).is(":checked");

                    var match = name.match(
                        /^permissions\[([^\]]+)\]\[([^\]]+)\]$/
                    );
                    if (match) {
                        var menu = match[1];
                        var action = match[2];
                        if (!permissions[menu]) {
                            permissions[menu] = {};
                        }
                        permissions[menu][action] = checked ? 1 : 0;
                    }
                });

                if ($("#add_role_name").val() == "") {
                    _show_toastr(
                        "error",
                        "Role name is required",
                        "User Error"
                    );
                    return;
                }
                var data = {
                    ID: $(this).attr("data-id"),
                    Role: $("#add_role_name").val(),
                    Description: $("#add_role_description").val(),
                    Permissions: JSON.stringify(permissions),
                };

                ajaxRequest("/executor/account/update-role", data, "");

                break;
            default:
                break;
        }
    });
});

function _fetchRoles() {
    ajaxRequest("/executor/account/get-roles", {}, "roles");
}

function toggleAllPermission(type, el) {
    const checkboxes = document.querySelectorAll(
        'input[type="checkbox"][name^="permissions"][name$="[' + type + ']"]'
    );
    checkboxes.forEach((cb) => {
        cb.checked = el.checked;
    });
}

function _init_actions() {
    $("[data-trigger='edit-role']").off();
    $("[data-trigger='edit-role']").on("click", function () {
        var id = $(this).data("id");
        ajaxRequest("/executor/account/edit-role", { id: id }, "edit-role");
    });

    $("[data-trigger='delete-role']").off();
    $("[data-trigger='delete-role']").on("click", function () {
        var ID = $(this).data("id");
        _confirm(
            "Are you sure you want to delete this role?",
            "Please be reminded that this action is irreversible!",
            "warning",
            "Yes, continue",
            true,
            function () {
                ajaxRequest("/executor/account/delete-role", { ID: ID }, "");
            }
        );
    });
}
