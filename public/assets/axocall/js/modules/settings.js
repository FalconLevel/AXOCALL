$(document).ready(function () {
    _fetch_tags();
    _fetch_extension_settings();
    init_actions();
});

function _fetch_tags() {
    ajaxRequest("/executor/tags/all", {}, "");
}

function _fetch_extension_settings() {
    ajaxRequest("/executor/settings/extension-settings", {}, "");
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

                ajaxRequest(
                    "/executor/settings/save-extension-settings",
                    data,
                    "POST"
                );
                break;
        }
    });
}
