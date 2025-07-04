$(document).ready(function () {
    $("button[data-trigger='sign-up-via-email']").click(function (e) {
        e.preventDefault();

        $(".sign-up-category").removeClass("d-none").addClass("d-none");
        $(".sign-up-form").removeClass("d-none");
    });

    $("a[data-trigger='sign-up-category']").click(function (e) {
        e.preventDefault();

        $(".sign-up-category").removeClass("d-none");
        $(".sign-up-form").removeClass("d-none").addClass("d-none");
    });

    $("button[data-trigger='sign-up-submit']").click(function (e) {
        e.preventDefault();
        let parent = $(this).closest("form");
        let isFormValid = _checkFormFields(parent);

        if (!isFormValid) {
            _show_toastr("error", "Please fill all required fields", "Error");
            return;
        }

        let formData = JSON.parse(_collectFields(parent));
        if (formData.Password !== formData.ConfirmPassword) {
            _show_toastr("error", "Passwords did not match", "Error");
            return;
        }

        if (formData.Password.length < 8) {
            _show_toastr(
                "error",
                "Password must be at least 8 characters long",
                "Error"
            );
            return;
        }

        if (formData.PhoneNumber.length !== 10) {
            _show_toastr("error", "Phone number must be 10 digits", "Error");
            return;
        }

        if (!formData.Email.includes("@")) {
            _show_toastr("error", "Invalid email address", "Error");
            return;
        }

        ajaxRequest("/executor/account/register", formData, "");
    });
});
