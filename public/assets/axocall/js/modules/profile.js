$(document).ready(function () {
    $('[data-trigger="save-profile"]').off();
    $('[data-trigger="save-profile"]').on("click", function (e) {
        e.preventDefault();
        let parent = $(this).closest("form");

        if (!_checkFormFields(parent)) {
            _show_toastr(
                "error",
                "Please fill all the required fields",
                "User Error"
            );
            return false;
        }
        let json_data = JSON.parse(_collectFields(parent));
        ajaxRequest("/executor/profile/update", json_data, "");
    });

    $('[data-trigger="edit-profile"]').off();
    $('[data-trigger="edit-profile"]').on("click", function (e) {
        e.preventDefault();
        let parent = $(this).closest("form");
        parent.find(".form-control-static").toggleClass("form-control-static");
        parent.find(".card-footer").toggleClass("d-none");
    });
});
