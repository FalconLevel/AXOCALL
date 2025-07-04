$(document).ready(function () {
    $('[data-trigger="un-archive"]').off();
    $('[data-trigger="un-archive"]').on("click", function () {
        const id = $(this).data("id");
        const type = $(this).data("type");
        const url = "/api/communications/un-archive/" + id + "/" + type;
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='_token']").attr("content"),
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

    $('[data-trigger="archive"]').off();
    $('[data-trigger="archive"]').on("click", function () {
        const id = $(this).data("id");
        const type = $(this).data("type");
        const url = "/api/communications/archive/" + id + "/" + type;
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='_token']").attr("content"),
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

    $('[data-trigger="un-follow-up"]').off();
    $('[data-trigger="un-follow-up"]').on("click", function () {
        const id = $(this).data("id");
        const type = $(this).data("type");
        const url = "/api/communications/un-follow-up/" + id + "/" + type;
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='_token']").attr("content"),
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
});
