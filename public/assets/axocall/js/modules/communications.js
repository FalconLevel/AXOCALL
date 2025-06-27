$(document).ready(function () {
    _initWidgets();
    $("table").on("draw.dt", function () {
        _initWidgets();
    });
});

function _initWidgets() {
    $('[data-trigger="recording"]').off();
    $('[data-trigger="recording"]').on("click", function () {
        const recordingUrl = $(this).data("recording-url");

        if (recordingUrl) {
            $("#audio-modal").find("audio").attr("src", recordingUrl);
            $("#audio-modal").modal("show");

            $("#audio-modal").on("hidden.bs.modal", function () {
                $("#audio-modal").find("audio").attr("src", "");
            });
        }
    });

    // Message body view functionality
    $(".view-message").off();
    $(".view-message").on("click", function () {
        const messageBody = $(this).data("message");
        $("#messageBodyText").text(messageBody);
        $("#messageBodyModal").modal("show");
    });

    // Message archive functionality
    $('[data-trigger="archive"]').off();
    $('[data-trigger="archive"]').on("click", function () {
        const id = $(this).data("id");

        const type = $(this).data("type");
        const url =
            type == "message"
                ? "/api/communications/archive/" + id + "/message"
                : "/api/communications/archive/" + id + "/communication";

        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='_token']").attr("content"),
            },
            data: {
                id: id,
                type: type,
            },
            success: function (response) {
                if (response.success) {
                    _show_toastr("success", response.message);
                    location.reload();
                } else {
                    _show_toastr("error", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                _show_toastr("error", xhr.responseText);
            },
        });
    });
}
