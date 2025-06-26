$(document).ready(function () {
    _fetchExtensions();
    _init_extension_actions();
});

function _fetchExtensions() {
    $.ajax({
        url: "/api/extensions/all",
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            if (res.status === "success") {
                _renderExtensions(res.data);
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to fetch extensions",
                    "System Error"
                );
            }
        },
        error: function () {
            _show_toastr("error", "Failed to fetch extensions", "System Error");
        },
    });
}

function _renderExtensions(extensions) {
    console.log(extensions);
    let tbody = $(".extensions-table tbody");
    tbody.empty();
    if (!extensions || extensions.length === 0) {
        tbody.append('<tr><td colspan="5">No extensions found.</td></tr>');
        return;
    }
    extensions.forEach(function (extension) {
        tbody.append(`
            <tr>
                <td>${
                    extension.contact.first_name
                } ${extension.contact.last_name}</td>
                <td>${extension.phone.phone_number}</td>
                <td>${extension.extension_number}</td>
                <td>${extension.status || ""}</td>
                <td>${extension.notes || ""}</td>
                <td>${_format_date(extension.created_at) || ""}</td>
                <td>${_format_date(extension.expiration) || ""}</td>
                <td>
                    <span>
                        <a href="#" data-trigger="edit-extension" data-id="${
                            extension.id
                        }" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>
                        <a href="#" data-trigger="delete-extension" data-id="${
                            extension.id
                        }" title="Delete"><i class="fa fa-trash color-danger"></i></a>
                    </span>
                </td>
            </tr>
        `);
    });
    _init_extension_actions();
}

function _editExtension(id) {
    $.ajax({
        url: `/api/extensions/edit/${id}`,
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            if (res.status === "success" && res.data) {
                _populateExtensionModal(res.data);
                $("#extension-modal .modal-title").text("Edit Extension");
                $('#extension-modal [data-trigger="save-extension"]')
                    .attr("data-mode", "edit")
                    .attr("data-id", id);
                $("#extension-modal").modal("show");
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to fetch extension",
                    "System Error"
                );
            }
        },
        error: function () {
            _show_toastr("error", "Failed to fetch extension", "System Error");
        },
    });
}

function _deleteExtension(id) {
    if (!confirm("Are you sure you want to delete this extension?")) return;
    $.ajax({
        url: `/api/extensions/delete/${id}`,
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            if (res.status === "success") {
                _show_toastr(
                    "success",
                    "Extension deleted successfully",
                    "System Info"
                );
                _fetchExtensions();
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to delete extension",
                    "System Error"
                );
            }
        },
        error: function () {
            _show_toastr("error", "Failed to delete extension", "System Error");
        },
    });
}

function _populateExtensionModal(extension) {
    let modal = $("#extension-modal");
    modal
        .find('[data-key="extension_number"]')
        .val(extension.extension_number || "");
    modal.find('[data-key="expiration"]').val(extension.expiration || "");
    modal.find('[data-key="notes"]').val(extension.notes || "");
}

function _init_extension_actions() {
    $("[data-trigger]").off();
    $("[data-trigger]").click(function (e) {
        e.preventDefault();
        let trigger = $(this).data("trigger");
        console.log(trigger);
        let parentForm = $(this).closest("form");
        switch (trigger) {
            case "add-extension":
                let modal = $(this).data("modal");
                if (modal === "extension") {
                    // Reset modal for add
                    $("#extensions-modal form")[0].reset();
                    $("#extensions-modal .modal-title").text(
                        "Add New Extension"
                    );
                    $('#extensions-modal [data-trigger="save-extension"]')
                        .attr("data-mode", "add")
                        .removeAttr("data-id");
                }
                _show_numbers();
                $("#" + modal).modal("show");
                break;
            case "save-extension":
                let extension_details = _collectExtensionFields(parentForm);
                if (!extension_details) return;
                let mode = $(this).attr("data-mode");
                let extensionId = $(this).attr("data-id");

                console.log(extension_details);
                let url =
                    mode === "edit"
                        ? `/api/extensions/update/${extensionId}`
                        : "/api/extensions/save";
                $.ajax({
                    url: url,
                    method: "POST",
                    data: extension_details,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="_token"]').attr(
                            "content"
                        ),
                    },
                    success: function (res) {
                        if (res.status === "success") {
                            _show_toastr(
                                "success",
                                res.message || "Extension saved successfully",
                                "System Info"
                            );
                            $("#extension-modal").modal("hide");
                            _fetchExtensions();
                        } else {
                            _show_toastr(
                                "error",
                                res.message || "Failed to save extension",
                                "System Error"
                            );
                        }
                    },
                    error: function () {
                        _show_toastr(
                            "error",
                            "Failed to save extension",
                            "System Error"
                        );
                    },
                });
                break;
            case "edit-extension":
                let editId = $(this).data("id");
                _editExtension(editId);
                break;
            case "delete-extension":
                let deleteId = $(this).data("id");
                _deleteExtension(deleteId);
                break;
        }
    });
}

function _collectExtensionFields(form) {
    let contact_id = form.find('[data-key="contact_id"]').val();
    let phone_number = form.find('[data-key="phone_number"]').val();
    let phone_ext = form.find('[data-key="phone_ext"]').val();
    let phone_type = form.find('[data-key="phone_type"]').val();
    let phone_id = form.find('[data-key="phone_id"]').val();
    let extension_number = form.find('[data-key="extension_number"]').val();
    let expiration = form.find('[data-key="expiration"]').val();
    let notes = form.find('[data-key="notes"]').val();

    if (!extension_number) {
        _show_toastr("error", "Extension number is required", "User Error");
        return false;
    }
    return {
        contact_id: contact_id,
        phone_id: phone_number,
        extension_number: extension_number,
        expiration: expiration,
        notes: notes,
    };
}

function _format_date(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
}

function _show_numbers() {
    // When the contact is changed, determine if phone_number dropdown should be shown or not
    $("#contact_id").on("change", function () {
        var contactId = $(this).val();
        var $phoneSelect = $("#phone_number");
        var $contactInfo = $("#selected-contact-info");
        var $contactInfoDisplay = $("#contact-info-display");
        $phoneSelect.empty();
        $phoneSelect.append('<option value="">Select Phone Number</option>');
        $contactInfo.hide();
        $contactInfoDisplay.empty();

        if (contactId) {
            $.ajax({
                url: "/api/contacts/" + contactId + "/phone-numbers",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
                },
                success: function (res) {
                    if (
                        res.status === "success" &&
                        res.data &&
                        res.data.length > 0
                    ) {
                        if (res.data.length > 1) {
                            // Show phone number dropdown, hide contact info
                            $phoneSelect.closest(".form-group").show();
                            $contactInfo.hide();
                            res.data.forEach(function (phone) {
                                var label = phone.phone_number;
                                if (phone.phone_type) {
                                    label += " (" + phone.phone_type + ")";
                                }
                                $phoneSelect.append(
                                    '<option value="' +
                                        phone.id +
                                        '">' +
                                        label +
                                        "</option>"
                                );
                            });
                        } else {
                            // Only one phone number, hide dropdown, show contact info
                            $phoneSelect.closest(".form-group").hide();
                            $contactInfo.show();
                            var phone = res.data[0];
                            // Optionally, you can set the value of phone_number input for form submission
                            $phoneSelect.html(
                                '<option value="' +
                                    phone.id +
                                    '" selected>' +
                                    phone.phone_number +
                                    (phone.phone_type
                                        ? " (" + phone.phone_type + ")"
                                        : "") +
                                    "</option>"
                            );
                            // Set contact info display
                            // You may want to fetch the contact name from the selected option in #contact_id
                            var contactName = $(
                                "#contact_id option:selected"
                            ).text();
                            $contactInfoDisplay.html(
                                "<strong>Name:</strong> " +
                                    contactName +
                                    "<br><strong>Phone:</strong> " +
                                    phone.phone_number +
                                    (phone.phone_type
                                        ? " (" + phone.phone_type + ")"
                                        : "")
                            );
                        }
                    } else {
                        $phoneSelect.closest(".form-group").show();
                        $contactInfo.hide();
                        $phoneSelect.append(
                            '<option value="">No phone numbers found</option>'
                        );
                    }
                },
                error: function (err) {
                    $phoneSelect.closest(".form-group").show();
                    $contactInfo.hide();
                    $phoneSelect.append(
                        '<option value="">Failed to load phone numbers</option>'
                    );
                },
            });
        } else {
            $phoneSelect.closest(".form-group").show();
            $contactInfo.hide();
        }
    });

    $("#phone_number").on("change", function () {
        var phoneNumber = $(this).val();
        var phoneText = $("#phone_number option:selected").text();
        var contactId = $("#contact_id").val();
        var contactName = $("#contact_id option:selected").text();

        if (phoneNumber && contactId) {
            $("#contact-info-display").html(
                "<strong>" +
                    contactName +
                    "</strong><br>" +
                    "<span>" +
                    phoneText +
                    "</span>"
            );
            $("#selected-contact-info").show();
        } else {
            $("#selected-contact-info").hide();
            $("#contact-info-display").empty();
        }
    });

    // Hide info if contact changes
    $("#contact_id").on("change", function () {
        $("#selected-contact-info").hide();
        $("#contact-info-display").empty();
    });
    // $("#contact_id").on("change", function () {
    //     var contactId = $(this).val();
    //     var $phoneSelect = $("#phone_number");
    //     $phoneSelect.empty();
    //     $phoneSelect.append('<option value="">Select Phone Number</option>');

    //     if (contactId) {
    //         $.ajax({
    //             url: "/api/contacts/" + contactId + "/phone-numbers",
    //             type: "POST",
    //             headers: {
    //                 "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
    //             },
    //             success: function (res) {
    //                 console.log(res);
    //                 if (
    //                     res.status === "success" &&
    //                     res.data &&
    //                     res.data.length > 0
    //                 ) {
    //                     res.data.forEach(function (phone) {
    //                         var label = phone.phone_number;
    //                         if (phone.phone_type) {
    //                             label += " (" + phone.phone_type + ")";
    //                         }
    //                         $phoneSelect.append(
    //                             '<option value="' +
    //                                 phone.phone_number +
    //                                 '">' +
    //                                 label +
    //                                 "</option>"
    //                         );
    //                     });
    //                 } else {
    //                     $phoneSelect.append(
    //                         '<option value="">No phone numbers found</option>'
    //                     );
    //                 }
    //             },
    //             error: function (err) {
    //                 console.log(err);
    //                 $phoneSelect.append(
    //                     '<option value="">Failed to load phone numbers</option>'
    //                 );
    //             },
    //         });
    //     }
    // });
    // ``;
}
