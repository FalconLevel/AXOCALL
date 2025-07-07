$(document).ready(function () {
    _fetchDashboardData();

    $(".nav-link").on("click", function () {
        const trigger = $(this).data("trigger");
        if (trigger == "dashboard-custom") {
            $(".input-daterange-datepicker").click();
            $(".input-daterange-datepicker").on(
                "apply.daterangepicker",
                function (ev, picker) {
                    console.log(picker);
                    const daterange =
                        picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY");
                    $(".input-daterange-datepicker").val(daterange);
                    _fetchDashboardData(trigger, daterange);
                }
            );
        } else {
            _fetchDashboardData(trigger);
        }
    });

    if ($(".input-daterange-datepicker").length > 0) {
        $(".input-daterange-datepicker").off();
        $(".input-daterange-datepicker").daterangepicker({
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-danger",
            cancelClass: "btn-inverse",
        });
    }
});

function _fetchDashboardData(trigger = "dashboard-today", daterange = null) {
    $.ajax({
        url: "/api/dashboard/stats",
        type: "GET",
        data: {
            trigger: trigger,
            daterange: daterange,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.status) {
                $(".total-communications").text(
                    response.data.total_communications
                );
                $(".total-appointments-booked").text(
                    response.data.total_appointments_booked
                );
                $(".total-extensions").text(response.data.total_extensions);
                $(".total-messages").text(response.data.total_messages);
                $(".total-follow-ups").text(response.data.total_follow_ups);
                $(".total-positive-calls").text(
                    response.data.total_positive_calls
                );
                $(".total-neutral-calls").text(
                    response.data.total_neutral_calls
                );
                $(".total-negative-calls").text(
                    response.data.total_negative_calls
                );
                $(".total-positive-calls-percentage").text(
                    response.data.total_calls_with_sentiment > 0
                        ? (
                              (response.data.total_positive_calls /
                                  response.data.total_calls_with_sentiment) *
                              100
                          ).toFixed(2) +
                              "% of " +
                              response.data.total_calls_with_sentiment
                        : "0% of 0"
                );
                $(".total-neutral-calls-percentage").text(
                    response.data.total_calls_with_sentiment > 0
                        ? (
                              (response.data.total_neutral_calls /
                                  response.data.total_calls_with_sentiment) *
                              100
                          ).toFixed(2) +
                              "% of " +
                              response.data.total_calls_with_sentiment
                        : "0% of 0"
                );
                $(".total-negative-calls-percentage").text(
                    response.data.total_calls_with_sentiment > 0
                        ? (
                              (response.data.total_negative_calls /
                                  response.data.total_calls_with_sentiment) *
                              100
                          ).toFixed(2) +
                              "% of " +
                              response.data.total_calls_with_sentiment
                        : "0% of 0"
                );
            }
        },
    });
}
