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
                            <input type="text" class="form-control form-control-xs phone-number-edit phone-number" maxlength="10"
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
                            selectedTagsContainer.append(`
                                <span class="mr-1 p-2 text-white label label-pill selected-tag-edit" 
                                      style="background-color: ${tag.tag.tag_color} !important;"
                                      data-tag-id="${tag.tag_id}">
                                    <i class="fa fa-tag"></i>
                                    ${tag.tag.tag_name}
                                    <i class="fa fa-times ml-1 cursor-pointer" data-trigger="remove-tag-edit"></i>
                                </span>
                            `);
                        });
                    }

                    $("#contact-modal-edit").modal("show");
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

    // Add phone number in edit modal
    $(document).on("click", '[data-trigger="add-phone-edit"]', function () {
        const phoneContainer = $(".phone-card-container-edit");
        const currentIndex = phoneContainer.find(".phone-card-edit").length;
        phoneContainer.append(createPhoneCardEdit(null, currentIndex));

        // Update phone counts
        phoneContainer.find(".phone-card-edit").each(function (index) {
            $(this)
                .find(".phone-count")
                .text(index + 1);
            if (index > 0) {
                $(this)
                    .find('[data-trigger="remove-phone-edit"]')
                    .removeClass("d-none");
            }
        });
    });

    // Remove phone number in edit modal
    $(document).on("click", '[data-trigger="remove-phone-edit"]', function () {
        $(this).closest(".phone-card-edit").remove();

        // Update phone counts
        $(".phone-card-container-edit .phone-card-edit").each(function (index) {
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
        });
    });

    // Select tag in edit modal
    $(document).on("click", '[data-trigger="select-tag-edit"]', function () {
        const tagId = $(this).data("id");
        const tagName = $(this).text().trim();
        const tagColor = $(this).css("background-color");

        // Check if tag is already selected
        if ($(`.selected-tag-edit[data-tag-id="${tagId}"]`).length === 0) {
            $(".selected-tags-edit").append(`
                <span class="mr-1 p-2 text-white label label-pill selected-tag-edit" 
                      style="background-color: ${tagColor} !important;"
                      data-tag-id="${tagId}">
                    <i class="fa fa-tag"></i>
                    ${tagName}
                    <i class="fa fa-times ml-1 cursor-pointer" data-trigger="remove-tag-edit"></i>
                </span>
            `);
        }
    });

    // Remove tag in edit modal
    $(document).on("click", '[data-trigger="remove-tag-edit"]', function () {
        $(this).closest(".selected-tag-edit").remove();
    });

    // Update contact
    $(document).on("click", '[data-trigger="update-contact"]', function () {
        const contactId = $("#contact_id").val();
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
        const selectedTags = [];
        $(".selected-tag-edit").each(function () {
            selectedTags.push($(this).data("tag-id"));
        });

        // Prepare data
        const contactData = {
            FirstName: firstName,
            LastName: lastName,
            Notes: notes,
            PhoneNumbers: phoneNumbers,
            Tags: selectedTags,
        };

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
    });

    // Clear modal when closed
    $("#contact-modal-edit").on("hidden.bs.modal", function () {
        $("#contact-edit-form")[0].reset();
        $(".phone-card-container-edit").empty();
        $(".selected-tags-edit").empty();
    });
});
