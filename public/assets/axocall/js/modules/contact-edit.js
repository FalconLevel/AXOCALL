$(document).ready(function () {
    // Template for phone card in edit modal
    function createPhoneCardEdit(phoneData = null, index = 0) {
        const phoneNumber = phoneData ? phoneData.phone_number : "";
        const phoneExt = phoneData ? phoneData.phone_ext || "" : "";
        const phoneType = phoneData ? phoneData.phone_type || "" : "";
        const showDelete = index > 0 ? "" : "d-none";

        return `
            <div class="card shadow-none p-1 phone-card-edit mb-2" data-phone-index="${index}">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between">
                        <label class="text-label">
                            Phone <span class="phone-count">${index + 1}</span>
                        </label>
                        <i class="fa fa-trash cursor-pointer text-danger ${showDelete}" data-trigger="remove-phone-edit"></i>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-xs phone-number-edit phone-number"
                                   placeholder="Phone Number" value="${phoneNumber}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-xs phone-ext-edit" 
                                   placeholder="1234#" value="${phoneExt}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-xs phone-type-edit" 
                                   placeholder="Home" value="${phoneType}">
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Load contact data for editing
    window.loadContactForEdit = function (contactId) {
        $.ajax({
            url: `/api/contacts/edit/${contactId}`,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
            success: function (response) {
                if (response.status === "success") {
                    const contact = response.data;

                    // Populate basic fields
                    $("#contact_id").val(contact.id);
                    $("#edit_first_name").val(contact.first_name);
                    $("#edit_last_name").val(contact.last_name);
                    $("#edit_notes").val(contact.notes);

                    // Populate phone numbers
                    const phoneContainer = $(".phone-card-container-edit");
                    phoneContainer.empty();

                    if (
                        contact.phone_numbers &&
                        contact.phone_numbers.length > 0
                    ) {
                        contact.phone_numbers.forEach((phone, index) => {
                            phoneContainer.append(
                                createPhoneCardEdit(phone, index)
                            );
                        });
                    } else {
                        phoneContainer.append(createPhoneCardEdit(null, 0));
                    }

                    // Populate selected tags
                    const selectedTagsContainer = $(".selected-tags-edit");
                    selectedTagsContainer.empty();

                    if (contact.tags && contact.tags.length > 0) {
                        contact.tags.forEach((tag) => {
                            console.log(tag);
                            selectedTagsContainer.append(`
                                <span class="mr-1 p-2 text-white tag-labels label label-pill selected-tag-edit" 
                                      style="background-color: ${tag.tag.tag_color} !important;"
                                      data-tag-id="${tag.tag_id}">
                                    <i class="fa fa-tag"></i>
                                    ${tag.tag.tag_name}
                                    <a href="javascript:void(0)" class="text-white" data-id="${tag.tag_id}" data-trigger="deselect-tag-edit">
                                        <i class="fa fa-times ml-1 cursor-pointer"></i>
                                    </a>
                                </span>
                            `);

                            $(".existing-tags-edit")
                                .find(`[data-id="${tag.tag_id}"]`)
                                .remove();
                        });
                    }

                    if ($(".phone-number").length) {
                        $(".phone-number").on("keyup", function (event) {
                            // Only allow numeric input
                            let input = $(this);
                            let value = input.val();
                            let numericValue = value.replace(/[^0-9]/g, "");

                            // Limit to 10 digits
                            if (numericValue.length > 10) {
                                numericValue = numericValue.substring(0, 10);
                            }

                            // Update the input value
                            input.val(numericValue);
                        });
                    }

                    $("#contact-modal-edit").modal("show");
                    _init_edit_actions();
                }
            },
            error: function (xhr) {
                _show_toastr(
                    "error",
                    "Error loading contact data",
                    "System Error"
                );
            },
        });
    };

    function _init_edit_actions() {
        $("[data-trigger]").off();
        $("[data-trigger]").click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            let trigger = $(this).data("trigger");
            let parentForm = $(this).closest("form");
            switch (trigger) {
                case "add-phone-edit":
                    const phoneContainer = $(".phone-card-container-edit");
                    const currentIndex =
                        phoneContainer.find(".phone-card-edit").length;
                    phoneContainer.append(
                        createPhoneCardEdit(null, currentIndex)
                    );

                    // Update phone counts
                    phoneContainer
                        .find(".phone-card-edit")
                        .each(function (index) {
                            $(this)
                                .find(".phone-count")
                                .text(index + 1);
                            if (index > 0) {
                                $(this)
                                    .find('[data-trigger="remove-phone-edit"]')
                                    .removeClass("d-none");
                            }
                        });
                    break;
                case "remove-phone-edit":
                    $(this).closest(".phone-card-edit").remove();
                    // Update phone counts
                    $(".phone-card-container-edit .phone-card-edit").each(
                        function (index) {
                            $(this)
                                .find(".phone-count")
                                .text(index + 1);
                            $(this).attr("data-phone-index", index);
                            if (index === 0) {
                                $(this)
                                    .find('[data-trigger="remove-phone-edit"]')
                                    .addClass("d-none");
                            } else {
                                $(this)
                                    .find('[data-trigger="remove-phone-edit"]')
                                    .removeClass("d-none");
                            }
                        }
                    );
                    break;
                case "select-tag-edit":
                    let selected_tag_id = $(this).data("id");
                    let selected_tag_name = $(this).text();
                    let selected_tag_color = $(this).css("background-color");
                    let selected_tag_html =
                        '<span class="mr-1 p-2 text-white label label-pill tag-labels" style="background-color: ' +
                        selected_tag_color +
                        ' !important;">' +
                        selected_tag_name +
                        "<a href='javascript:void(0)' class='text-white' data-trigger='deselect-tag-edit' data-id='" +
                        selected_tag_id +
                        "'>" +
                        "<i class='fa fa-times'></i></a></span>";
                    $(".selected-tags-edit").append(selected_tag_html);

                    $(this).remove();

                    _init_edit_actions();
                    break;
                case "deselect-tag-edit":
                    let deselected_tag_id = $(this)
                        .closest(".tag-labels")
                        .attr("data-tag-id");
                    let deselected_tag_name = $(this)
                        .closest(".tag-labels")
                        .text();
                    let deselected_tag_color = $(this)
                        .closest(".tag-labels")
                        .css("background-color");
                    let deselected_tag_html =
                        '<span class="mr-1 p-2 text-white label label-pill tag-labels cursor-pointer" data-trigger="select-tag-edit" data-id="' +
                        deselected_tag_id +
                        '" style="background-color: ' +
                        deselected_tag_color +
                        ' !important;"><i class="fa fa-tag"></i> ' +
                        deselected_tag_name +
                        "</span>";
                    console.log(deselected_tag_html);
                    $(".existing-tags-edit").append(deselected_tag_html);
                    $(this).closest(".tag-labels").remove();

                    _init_edit_actions();
                    break;
                case "update-contact":
                    const contactId = $("#contact_id").val();
                    _updateContact(contactId);
                    break;
            }
        });
    }
});

function _updateContact(contactId) {
    const firstName = $("#edit_first_name").val();
    const lastName = $("#edit_last_name").val();
    const notes = $("#edit_notes").val();

    // Validate required fields
    if (!firstName) {
        _show_toastr("error", "First Name is required", "User Error");
        return;
    }

    // Collect phone numbers
    const phoneNumbers = [];
    $(".phone-card-edit").each(function () {
        const phoneNumber = $(this).find(".phone-number-edit").val();
        const phoneExt = $(this).find(".phone-ext-edit").val();
        const phoneType = $(this).find(".phone-type-edit").val();

        if (phoneNumber) {
            if (phoneNumber === "") {
                _show_toastr(
                    "error",
                    "Please fill all required phone fields",
                    "User Error"
                );
                return;
            }

            // Validate phone number to be exactly 10 digits
            if (!/^\d{10}$/.test(phoneNumber.replace(/\D/g, ""))) {
                _show_toastr(
                    "error",
                    "Phone number must be exactly 10 digits",
                    "User Error"
                );
                return;
            }

            phoneNumbers.push({
                phone_number: phoneNumber,
                phone_ext: phoneExt,
                phone_type: phoneType,
            });
        }
    });

    if (phoneNumbers.length === 0) {
        _show_toastr(
            "error",
            "At least one phone number is required",
            "System Error"
        );
        return;
    }

    // Collect selected tags
    let selectedTags = _get_tags();

    // Prepare data
    const contactData = {
        FirstName: firstName,
        LastName: lastName,
        Notes: notes,
        PhoneNumbers: phoneNumbers,
        Tags: selectedTags,
    };

    console.log(contactData);

    // Send update request
    $.ajax({
        url: `/api/contacts/update/${contactId}`,
        method: "POST",
        data: contactData,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
        success: function (response) {
            if (response.status === "success") {
                $("#contact-modal-edit").modal("hide");
                _show_toastr(
                    "success",
                    "Contact updated successfully",
                    "System Info"
                );
                _fetchContacts();
            } else {
                _show_toastr(
                    "error",
                    response.message || "Failed to update contact",
                    "System Error"
                );
            }
        },
        error: function (xhr) {
            const response = xhr.responseJSON;
            _show_toastr(
                "error",
                response.message || "Failed to update contact",
                "System Error"
            );
        },
    });

    // $("#contact-modal-edit").on("hidden.bs.modal", function () {
    //     $("#contact-edit-form")[0].reset();
    //     $(".phone-card-container-edit").empty();
    //     $(".selected-tags-edit").empty();
    // });
}

// Clear modal when closed
function _get_tags() {
    let tags = [];
    let selected_tags = $(".selected-tags-edit .tag-labels a");
    for (const tag of selected_tags) {
        tags.push($(tag).data("id"));
    }

    return tags;
}
