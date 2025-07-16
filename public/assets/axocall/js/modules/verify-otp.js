let interval = null;
$(document).ready(function () {
    $("[data-trigger='verify-otp-submit']").click(function (e) {
        e.preventDefault();
        var otp = $("[data-key='Otp']").val();
        if (!otp) {
            _show_toastr("error", "Please enter OTP", "System Info");
            return;
        }

        $.ajax({
            url: "/executor/validate-otp",
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            data: {
                token: $("#token").val(),
                otp: otp,
            },
            success: function (response) {
                console.log(response);
                if (response.status) {
                    eval(response.js);
                } else {
                    _show_toastr("error", response.message, "System Info");
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
        });
    });

    interval = setInterval(_expirationCountdown, 1000);
});

function _expirationCountdown() {
    $.ajax({
        url: "/executor/countdown",
        type: "GET",
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        data: {
            token: $("#token").val(),
        },
        success: function (response) {
            $("#expiry-countdown").html(response.message);
            if (!response.status) {
                clearInterval(interval);
            }

            $("#resend-otp").click(function () {
                let id = $(this).attr("data-id");
                $.ajax({
                    url: "/executor/resend-otp",
                    type: "GET",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    data: { id: id },
                    success: function (response) {
                        console.log(response);
                        _show_toastr(
                            "success",
                            response.message,
                            "System Info"
                        );
                        eval(response.js);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        _show_toastr(
                            "error",
                            "Failed to resend OTP",
                            "System Error"
                        );
                    },
                });
            });
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        },
    });
}
