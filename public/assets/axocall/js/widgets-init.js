$(document).ready(function () {
    if ($(".colorpicker").length) {
        $(".colorpicker").asColorPicker();
    }

    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: false,
        positionClass: "toast-bottom-right",
        preventDuplicates: false,
    };
});

function _show_toastr(type = "success", message = "", title = "") {
    toastr[type](message, title);
}
