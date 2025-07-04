$(document).ready(function () {
    _fetchContacts();
    _init_actions();
});

function _fetchContacts() {
    $.ajax({
        url: "/api/contacts/all",
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            if (res.status === "success") {
                _renderContacts(res.data);
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to fetch contacts",
                    "System Error"
                );
            }
        },
        error: function (err) {
            console.log(err);
            _show_toastr("error", "Failed to fetch contacts", "System Error");
        },
    });
}

function _renderContacts(contacts) {
    let tbody = $(".table tbody");
    tbody.empty();
    if (!contacts || contacts.length === 0) {
        tbody.append('<tr><td colspan="5">No contacts found.</td></tr>');
        return;
    }
    contacts.forEach(function (contact) {
        let tags = (contact.tags || [])
            .map(
                (tag) =>
                    `<span class="label label-pill tag-labels text-white" style="background:${tag.tag.tag_color}">${tag.tag.tag_name}</span>`
            )
            .join(" ");
        tbody.append(`
            <tr class="cursor-pointer" data-trigger="view-contact" data-id="${
                contact.id
            }">
                <td>${contact.first_name} ${contact.last_name || ""}</td>
                <td>${
                    contact.phone_numbers && contact.phone_numbers.length > 0
                        ? contact.phone_numbers
                              .map(
                                  (phone) =>
                                      phone.phone_number +
                                      " " +
                                      `<span class="label label-pill">${
                                          phone.phone_type || ""
                                      }</span>`
                              )
                              .join("<br />")
                        : ""
                }</td>
                <td>${tags}</td>
                <td>${contact.notes || ""}</td>
                <td>${_format_date(contact.created_at) || ""}</td>
                <td>
                    <span>
                        
                        <a href="#" data-trigger="delete-contact" data-id="${
                            contact.id
                        }" title="Delete"><i class="fa fa-trash text-danger"></i></a>
                    </span>
                </td>
            </tr>
        `);
    });
    // <a href="#" data-trigger="edit-contact" data-id="${
    //     contact.id
    // }" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>
    _init_actions();
}

function _editContact(id) {
    $.ajax({
        url: `/api/contacts/edit/${id}`,
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            console.log(res);
            if (res.status === "success" && res.data) {
                loadContactForEdit(id);
                $("#contact-modal-edit .modal-title").text("Edit Contact");
                $('#contact-modal-edit [data-trigger="save-contact"]')
                    .attr("data-mode", "edit")
                    .attr("data-id", id);
                $("#contact-modal-edit").modal("show");
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to fetch contact",
                    "System Error"
                );
            }
        },
        error: function (e) {
            console.log(e);
            _show_toastr("error", "Failed to fetch contact", "System Error");
        },
    });
}

function _deleteContact(id) {
    // if (!confirm("Are you sure you want to delete this contact?")) return;
    $.ajax({
        url: `/api/contacts/delete/${id}`,
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            if (res.status === "success") {
                _show_toastr(
                    "success",
                    "Contact deleted successfully",
                    "System Info"
                );
                _fetchContacts();
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to delete contact",
                    "System Error"
                );
            }
        },
        error: function () {
            _show_toastr("error", "Failed to delete contact", "System Error");
        },
    });
}

function _populateContactModal(contact) {
    let modal = $("#contacts-modal");
    modal.find('[data-key="FirstName"]').val(contact.first_name || "");
    modal.find('[data-key="LastName"]').val(contact.last_name || "");
    modal.find('[data-key="Notes"]').val(contact.notes || "");
    // Phones
    let phoneContainer = modal.find(".phone-card-container");

    if (contact.phone_numbers && contact.phone_numbers.length > 0) {
        (contact.phone_numbers || [{}]).forEach(function (phone, idx) {
            let card = $(modal.find(".phone-card").first().clone());
            card.find(".phone-number").val(phone.phone_number || "");
            card.find(".phone-ext").val(phone.phone_ext || "");
            card.find(".phone-type").val(phone.phone_type || "");
            card.find(".phone-count").text(idx + 1);
            card.find('[data-trigger="remove-phone"]').toggleClass(
                "d-none",
                (contact.phone_numbers || []).length === 1
            );
            phoneContainer.append(card);
        });
    }
    // Tags
    let selectedTags = modal.find(".selected-tags");
    let existingTags = modal.find(".existing-tags");
    selectedTags.empty();
    existingTags.find(".tag-labels").removeClass("d-none");
    (contact.tags || []).forEach(function (tag) {
        let tagEl = existingTags.find(`[data-id="${tag.id}"]`).first();
        if (tagEl.length) {
            let tagHtml = `<span class="mr-1 p-2 text-white label label-pill tag-labels" style="background-color: ${tag.tag_color} !important;" data-id="${tag.id}">${tag.tag_name}<a href='javascript:void(0)' class='text-white' data-trigger='deselect-tag' data-id='${tag.id}'><i class='fa fa-trash'></i></a></span>`;
            selectedTags.append(tagHtml);
            tagEl.addClass("d-none");
        }
    });
}

function _init_actions() {
    $("[data-trigger]").off();
    $("[data-trigger]").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        let trigger = $(this).data("trigger");
        let parentForm = $(this).closest("form");
        switch (trigger) {
            case "modal":
                let modal = $(this).data("modal");
                if (modal === "contacts") {
                    // Reset modal for add
                    $("#contacts-modal form")[0].reset();
                    $("#contacts-modal .modal-title").text("Add New Contact");
                    $('#contacts-modal [data-trigger="save-contact"]')
                        .attr("data-mode", "add")
                        .removeAttr("data-id");
                    $("#contacts-modal .selected-tags").empty();
                    $("#contacts-modal .existing-tags .tag-labels").removeClass(
                        "d-none"
                    );
                }
                $("#" + modal + "-modal").modal("show");
                break;
            case "select-tag":
                let selected_tag_id = $(this).data("id");
                let selected_tag_name = $(this).text();
                let selected_tag_color = $(this).css("background-color");
                let selected_tag_html =
                    '<span class="mr-1 p-2 text-white label label-pill tag-labels " style="background-color: ' +
                    selected_tag_color +
                    ' !important;">' +
                    selected_tag_name +
                    "<a href='javascript:void(0)' class='text-white' data-trigger='deselect-tag' data-id='" +
                    selected_tag_id +
                    "'>" +
                    "<i class='fa fa-trash'></i></a></span>";
                $(".selected-tags").append(selected_tag_html);
                $(this).remove();

                _init_actions();
                break;
            case "deselect-tag":
                let deselected_tag_id = $(this)
                    .closest(".tag-labels")
                    .data("id");
                let deselected_tag_name = $(this).closest(".tag-labels").text();
                let deselected_tag_color = $(this)
                    .closest(".tag-labels")
                    .css("background-color");
                let deselected_tag_html =
                    '<span class="mr-1 p-2 text-white label label-pill tag-labels cursor-pointer" data-trigger="select-tag" data-id="' +
                    deselected_tag_id +
                    '" style="background-color: ' +
                    deselected_tag_color +
                    ' !important;"><i class="fa fa-tag"></i> ' +
                    deselected_tag_name +
                    "</span>";
                $(".existing-tags").append(deselected_tag_html);
                $(this).closest(".tag-labels").remove();

                _init_actions();
                break;

            case "add-phone":
                let phone_card_container = $(".phone-card-container");
                let new_phone_card_html = $(
                    ".phone-card-container .phone-card:first"
                ).clone();

                phone_card_container.append(new_phone_card_html);

                $("[data-trigger='remove-phone']").removeClass("d-none");
                _init_actions();
                _update_phone_count();
                break;

            case "remove-phone":
                $(this).closest(".phone-card").remove();
                if ($(".phone-card").length === 1) {
                    $("[data-trigger='remove-phone']").addClass("d-none");
                }
                _init_actions();
                _update_phone_count();
                break;

            case "save-contact":
                if (!_checkFormFields(parentForm)) {
                    _show_toastr(
                        "error",
                        "Please fill all required phone fields",
                        "User Error"
                    );
                    return;
                }

                let contact_details = JSON.parse(_collectFields(parentForm));
                let phones = _get_phones();
                let tags = _get_tags();

                if (!(contact_details && phones && tags)) return;

                let url = "/executor/contacts/save";

                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        ...contact_details,
                        PhoneNumbers: phones,
                        Tags: tags,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="_token"]').attr(
                            "content"
                        ),
                    },
                    success: function (res) {
                        console.log(res);
                        if (res.status === "success") {
                            _show_toastr(
                                "success",
                                res.message || "Contact saved successfully",
                                "System Info"
                            );
                            $("#contacts-modal").modal("hide");
                            _fetchContacts();
                        } else {
                            _show_toastr(
                                "error",
                                res.message || "Failed to save contact",
                                "System Error"
                            );
                        }
                    },
                    error: function (err) {
                        console.log(err);
                        _show_toastr(
                            "error",
                            "Failed to save contact",
                            "System Error"
                        );
                    },
                });
                break;
            case "edit-contact":
                let editId = $(this).data("id");
                _editContact(editId);
                break;
            case "edit-contact-view":
                let editIdView = $(this).data("id");
                $("#contact-modal-view").modal("hide");
                _editContact(editIdView);
                break;
            case "delete-contact":
                let deleteId = $(this).data("id");
                _deleteContact(deleteId);
                break;
            case "view-contact":
                let viewId = $(this).data("id");
                _viewContact(viewId);
                break;
        }
    });
}

function _update_phone_count() {
    let phone_cards = $(".phone-card");
    let phone_count = 1;
    for (const phone_card of phone_cards) {
        $(phone_card).find(".phone-count").text(phone_count);
        phone_count++;
    }
}

function _get_phones() {
    let is_valid = true;
    let phones = [];
    let phone_cards = $(".phone-card");
    for (const phone_card of phone_cards) {
        let phone_details = {
            phone_number: $(phone_card).find(".phone-number").val(),
            phone_ext: $(phone_card).find(".phone-ext").val(),
            phone_type: $(phone_card).find(".phone-type").val(),
        };

        phones.push(phone_details);
    }

    phones.forEach((phone) => {
        if (phone.phone_number === "") {
            _show_toastr(
                "error",
                "Please fill all required phone fields",
                "User Error"
            );
            is_valid = false;
            return;
        }

        // Validate phone number to be exactly 10 digits
        if (!/^\d{10}$/.test(phone.phone_number.replace(/\D/g, ""))) {
            _show_toastr(
                "error",
                "Phone number must be exactly 10 digits",
                "User Error"
            );
            is_valid = false;
            return;
        }
    });

    return is_valid ? phones : false;
}

function _get_tags() {
    let tags = [];
    let selected_tags = $(".selected-tags .tag-labels a");
    for (const tag of selected_tags) {
        tags.push($(tag).data("id"));
    }

    return tags;
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

function _viewContact(id) {
    $.ajax({
        url: `/api/contacts/view/${id}`,
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        success: function (res) {
            if (res.status === "success") {
                let contact = res.data;

                // Populate modal with contact data
                $("#contact-modal-view .modal-title").text(
                    contact.first_name + " " + contact.last_name ||
                        "Contact Details"
                );
                $("#contact-modal-view .view_name").text(
                    contact.first_name + " " + contact.last_name || "N/A"
                );
                $("#contact-modal-view .view_notes").text(
                    contact.notes || "No notes available"
                );
                $("#contact-modal-view .view_created").text(
                    _format_date(contact.created_at)
                );

                $(
                    "#contact-modal-view [data-trigger='edit-contact-view']"
                ).attr("data-id", id);

                let extension_html = "";
                let phone_numbers = "";
                if (contact.phone_numbers && contact.phone_numbers.length > 0) {
                    contact.phone_numbers.forEach((phone) => {
                        phone_numbers +=
                            phone.phone_number +
                            " " +
                            (phone.phone_ext ? "x" + phone.phone_ext : "") +
                            " " +
                            (phone.phone_type
                                ? "(" + phone.phone_type + ")"
                                : "") +
                            " " +
                            "<br />";
                        if (phone.extension) {
                            extension_html += `
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h6>${phone.phone_number}</h6>
                                        <span class="view_extension">${phone.extension.extension_number}</span>
                                    </div>
                                </div>
                            `;
                        } else {
                            extension_html += `
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h6>${phone.phone_number}</h6>
                                        <span class="view_extension mb-3">No extension assigned</span>
                                    </div>
                                </div>
                            `;
                        }
                    });
                }

                $("#contact-modal-view .view_phone_numbers").html(
                    phone_numbers
                );

                // Handle tags display
                let tagsHtml = "";
                if (contact.tags && contact.tags.length > 0) {
                    contact.tags.forEach((tag) => {
                        tagsHtml += `<span class="badge badge-primary me-1">${tag.name}</span>`;
                    });
                } else {
                    tagsHtml =
                        '<span class="text-muted">No tags assigned</span>';
                }

                $("#contact-modal-view .contact-tags").html(tagsHtml);
                if (extension_html) {
                    $("#contact-modal-view .extension-details").html(
                        extension_html
                    );
                }

                // Show the modal
                $("#contact-modal-view").modal("show");
            } else {
                _show_toastr(
                    "error",
                    res.message || "Failed to load contact details",
                    "Error"
                );
            }
        },
        error: function (err) {
            console.log(err);
            _show_toastr(
                "error",
                "Failed to load contact details",
                "System Error"
            );
        },
    });
}
