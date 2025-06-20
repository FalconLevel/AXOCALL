$(document).ready(function () {
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
});
