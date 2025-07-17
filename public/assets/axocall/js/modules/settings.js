$(document).ready(function () {
    _fetch_tags();
    _fetch_extension_settings();
    _fetch_keywords();
    _fetch_email_settings();
    init_actions();

    $("[data-key='Frequency']").change(function () {
        let frequency = $(this).val();
        if (frequency == "weekly") {
            $("#day-of-week-container").removeClass("d-none");
        } else {
            $("#day-of-week-container")
                .removeClass("d-none")
                .addClass("d-none");
        }
    });
});

function _fetch_tags() {
    ajaxRequest("/executor/tags/all", {}, "");
}

function _fetch_extension_settings() {
    ajaxRequest("/executor/settings/extension-settings", {}, "");
}

function _fetch_keywords() {
    ajaxRequest("/executor/settings/keyword-settings", {}, "");
}

function _fetch_email_settings() {
    ajaxRequest("/executor/settings/email-settings", {}, "");
}

function init_actions() {
    $("[data-trigger]").off();
    $("[data-trigger]").click(function (e) {
        e.preventDefault();
        let trigger = $(this).data("trigger");
        let parentForm = $(this).closest("form");
        switch (trigger) {
            case "add-tag":
                let fields = JSON.parse(_collectFields(parentForm));
                console.log(fields);
                ajaxRequest("/executor/tags/save", fields, "");
                break;
            case "delete-tag":
                let id = $(this).data("id");
                ajaxRequest("/executor/tags/delete/" + id, {}, "");
                break;
            case "save-extension-settings":
                let randomExtensionGeneration = $(
                    "#random-extension-toggle"
                ).is(":checked")
                    ? 1
                    : 0;
                let data = JSON.parse(_collectFields(parentForm));
                data = {
                    ...data,
                    IsRandomExtensionGeneration: randomExtensionGeneration,
                };
                console.log(data);

                ajaxRequest(
                    "/executor/settings/save-extension-settings",
                    data,
                    "POST"
                );
                break;
            case "save-keywords":
                let keywords = $("[data-key='Keywords']").val();

                if (keywords == "") {
                    _show_toastr(
                        "error",
                        "Please enter keywords",
                        "User Error"
                    );
                    return;
                }

                ajaxRequest(
                    "/executor/settings/save-keywords",
                    {
                        Keywords: keywords,
                    },
                    "POST"
                );
                break;
            case "save-email-settings":
                if (!_checkFormFields(parentForm)) {
                    _show_toastr(
                        "error",
                        "Please fill all the fields",
                        "User Error"
                    );
                    return;
                }

                let emailData = {
                    ...JSON.parse(_collectFields(parentForm)),
                    IsEnabled: $("#email-summaries-toggle").is(":checked")
                        ? 1
                        : 0,
                };
                console.log(emailData);

                ajaxRequest(
                    "/executor/settings/save-email-settings",
                    emailData,
                    "POST"
                );
                break;
        }
    });
}
