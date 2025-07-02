$(document).ready(function () {
    _initWidgets();
    $("table").on("draw.dt", function () {
        _initWidgets();
    });

    // Fixed: Remove <script> tag, avoid nested DOMContentLoaded, use jQuery for consistency
    // Delegate click event for dynamic rows using jQuery
    $(document).on("click", ".btn-show-transcription", function () {
        var id = $(this).data("id");
        var transcription = $(this).data("transcription");
        var modalBody = document.getElementById("transcriptionModalBody");
        // Set the modal body content to the transcription value (HTML safe)
        if (modalBody) {
            modalBody.innerHTML = transcription
                ? transcription
                      .map(function (transcription) {
                          return transcription.transcript_sentence + "<br>";
                      })
                      .join("")
                : "<div class='text-center text-muted'>No transcription available.</div>";
        }
        $("#transcriptionModal").modal("show");
    });

    // Notes
    $(document).on("click", ".btn-edit-notes", function () {
        let id = $(this).attr("data-id");
        let notes = $(this).attr("data-notes");

        $("#editNotesCommunicationId").val(id);
        $("#editNotesTextarea").val(notes);
        $("#editNotesModal").modal("show");
    });

    $("#editNotesForm").on("submit", function (e) {
        e.preventDefault();
        let id = $("#editNotesCommunicationId").val();
        let notes = $("#editNotesTextarea").val();
        let token = $("meta[name='_token']").attr("content");
        $.ajax({
            url: "/api/communications/update-notes/" + id,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='_token']").attr("content"),
            },
            data: {
                _token: token,
                notes: notes,
            },
            success: function (response) {
                if (response.success) {
                    $("#editNotesModal").modal("hide");
                    $("a[data-id=" + id + "]").attr("data-notes", notes);
                    _show_toastr("success", "Notes updated successfully.");
                } else {
                    _show_toastr(
                        "error",
                        response.message || "Failed to update notes."
                    );
                }
            },
            error: function (xhr) {
                _show_toastr(
                    "error",
                    xhr.responseText || "Failed to update notes."
                );
            },
        });
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
