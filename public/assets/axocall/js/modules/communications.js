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
}
