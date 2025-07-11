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
        console.log(json_data);
        ajaxRequest("/executor/profile/update", json_data, "");
    });
});
