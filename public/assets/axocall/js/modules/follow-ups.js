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
});
