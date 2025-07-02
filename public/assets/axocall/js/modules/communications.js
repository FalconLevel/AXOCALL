$(document).ready(function () {
    _initWidgets();
    $("table").on("draw.dt", function () {
        _initWidgets();
    });
    // refreshDataTab();

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
    $('[data-trigger="follow-up"]').off();
    $('[data-trigger="follow-up"]').on("click", function () {
        const id = $(this).data("id");
        const type = $(this).data("type");

        $(this)
            .removeClass("text-light")
            .removeClass("text-primary")
            .addClass("text-primary");
        const url =
            type == "message"
                ? "/api/communications/follow-up/" + id + "/message"
                : "/api/communications/follow-up/" + id + "/communication";

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

// Refresh data tab functionality
function refreshDataTab() {
    // Show loading state
    $("#data-tab").addClass("loading");

    // Fetch fresh data from the API
    $.ajax({
        url: "/api/communications/refresh-datatable",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $("meta[name='_token']").attr("content"),
        },
        data: {
            sentiment: $("#sentiment-filter").val(),
            type: $("#type-filter").val(),
            is_booked: $("#booking-filter").val(),
            date_from: $("#date-from-filter").val(),
            date_to: $("#date-to-filter").val(),
        },
        success: function (response) {
            if (response.success) {
                // Update the communications table with new data
                updateCommunicationsTable(response.data);

                // Update pagination if needed
                if (response.data.links) {
                    updatePagination(response.data.links);
                }

                _show_toastr("success", "Data refreshed successfully");
            } else {
                _show_toastr("error", "Failed to refresh data");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error refreshing data:", error);
            _show_toastr("error", "Error refreshing data: " + error);
        },
        complete: function () {
            // Remove loading state
            $("#data-tab").removeClass("loading");
        },
    });
}

// Helper function to update communications table
function updateCommunicationsTable(communications) {
    const tbody = $("#communications-table tbody");
    tbody.empty();

    communications.forEach(function (communication) {
        const row = `
                <tr>
                    <td>${communication.type || "-"}</td>
                    <td>${
                        communication.from_formatted ||
                        communication.from ||
                        "-"
                    }</td>
                    <td>${
                        communication.to_formatted || communication.to || "-"
                    }</td>
                    <td>${
                        communication.date_time
                            ? new Date(communication.date_time).toLocaleString()
                            : "-"
                    }</td>
                    <td>${communication.duration || "-"}</td>
                    <td>
                        <span class="badge badge-${getSentimentBadgeClass(
                            communication.sentiment
                        )}">
                            ${communication.sentiment || "N/A"}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-${
                            communication.is_booked === "yes"
                                ? "success"
                                : "secondary"
                        }">
                            ${
                                communication.is_booked === "yes"
                                    ? "Booked"
                                    : "Not Booked"
                            }
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            ${
                                communication.recording_filename
                                    ? `<button class="btn btn-sm btn-primary play-audio" data-recording="${communication.recording_filename}" data-call-sid="${communication.call_sid}">
                                    <i class="fas fa-play"></i>
                                </button>`
                                    : ""
                            }
                            <button class="btn btn-sm btn-info view-details" data-id="${
                                communication.id
                            }">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-trigger="follow-up" data-id="${
                                communication.id
                            }" data-type="communication">
                                <i class="fas fa-flag"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary" data-trigger="archive" data-id="${
                                communication.id
                            }" data-type="communication">
                                <i class="fas fa-archive"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        tbody.append(row);
    });

    // Re-bind event handlers for new elements
    bindTableEventHandlers();
}

// Helper function to get sentiment badge class
function getSentimentBadgeClass(sentiment) {
    switch (sentiment) {
        case "positive":
            return "success";
        case "negative":
            return "danger";
        case "neutral":
            return "info";
        default:
            return "secondary";
    }
}

// Helper function to update pagination
function updatePagination(links) {
    const paginationContainer = $("#communications-pagination");
    if (paginationContainer.length) {
        paginationContainer.html(links);
    }
}

// Helper function to bind event handlers for table elements
function bindTableEventHandlers() {
    // Re-bind audio play functionality
    $(".play-audio")
        .off()
        .on("click", function () {
            const recording = $(this).data("recording");
            const callSid = $(this).data("call-sid");
            playAudio(recording, callSid);
        });

    // Re-bind view details functionality
    $(".view-details")
        .off()
        .on("click", function () {
            const id = $(this).data("id");
            viewCommunicationDetails(id);
        });

    // Re-bind follow-up functionality
    $('[data-trigger="follow-up"]')
        .off()
        .on("click", function () {
            const id = $(this).data("id");
            const type = $(this).data("type");
            handleFollowUp(id, type);
        });

    // Re-bind archive functionality
    $('[data-trigger="archive"]')
        .off()
        .on("click", function () {
            const id = $(this).data("id");
            const type = $(this).data("type");
            handleArchive(id, type);
        });
}

// Bind refresh button click event
$("#refresh-data-btn")
    .off()
    .on("click", function () {
        refreshDataTab();
    });

// Auto-refresh functionality (optional - refresh every 5 minutes)
setInterval(function () {
    if ($("#data-tab").hasClass("active")) {
        refreshDataTab();
    }
}, 300000); // 5 minutes
