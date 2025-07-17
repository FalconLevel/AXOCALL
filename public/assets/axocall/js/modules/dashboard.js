$(document).ready(function () {
    _fetchDashboardData("", $(".input-daterange-datepicker").val());

    if ($(".input-daterange-datepicker").length > 0) {
        $(".input-daterange-datepicker").off();
        $(".input-daterange-datepicker").daterangepicker({
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-danger",
            cancelClass: "btn-inverse",
            locale: {
                format: "MM/DD/YYYY",
            },
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                Week: [moment().subtract(6, "days"), moment()],
                Month: [moment().subtract(29, "days"), moment()],
                AllTime: [moment("1970-01-01"), moment("1970-01-01")],
            },
            startDate: moment().subtract(6, "days"),
            endDate: moment(),
        });

        $(".input-daterange-datepicker").on(
            "apply.daterangepicker",
            function (ev, picker) {
                let daterange = "";
                let startDate = picker.startDate.format("YYYY-MM-DD");
                let endDate = picker.endDate.format("YYYY-MM-DD");

                if (startDate == "1970-01-01" && endDate == "1970-01-01") {
                    daterange = "All Time";
                } else {
                    daterange = startDate + " - " + endDate;
                }

                $(".input-daterange-datepicker").val(daterange);
                _fetchDashboardData("", daterange);
            }
        );
    }

    $("[data-trigger='keywords-details-modal']").on("click", function () {
        const keywordHits = JSON.parse($(this).attr("data-keyword-hits"));
        const totalCommunications = $(this).attr("data-total-communications");

        if (Object.keys(keywordHits).length > 0) {
            $("#keywords-details-modal-body").empty();
            for (const keyword in keywordHits) {
                const percentage =
                    (keywordHits[keyword] / totalCommunications) * 100;
                $("#keywords-details-modal-body").append(
                    ` <h5 class="mt-3">${keyword} 
                        <span class="float-right">
                            <font class="text-danger">
                                ${percentage.toFixed(1)}%
                            </font>
                            <font class="text-muted text-small">
                                (${keywordHits[keyword]}/${totalCommunications})
                            </font>
                        </span>
                    </h5>
                    <div class="progress mb-3" style="height: 9px">
                        <div class="progress-bar bg-danger wow  progress-" style="width: ${percentage}%;" role="progressbar">
                            <span class="sr-only">${
                                keywordHits[keyword]
                            }% </span>
                        </div>
                    </div>`
                );
            }

            $("#keywords-details-modal").modal("show");
        } else {
            _show_toastr("error", "No keyword hits found", "System Info");
        }
    });

    $("[data-trigger='export-dashboard']").on("click", function () {
        const daterange = $(".input-daterange-datepicker").val();

        $.ajax({
            url: "/api/dashboard/export",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
            data: {
                daterange: daterange,
            },
            success: function (response) {
                console.log(response);
                if (response.status) {
                    window.location.href = response.data;
                } else {
                    _show_toastr("error", response.message, "System Error");
                }
            },
            error: function (response) {
                console.log(response);
            },
        });
    });
});

function _fetchDashboardData(trigger = "", daterange = null) {
    $.ajax({
        url: "/api/dashboard/stats",
        type: "GET",
        data: {
            trigger: trigger,
            daterange: daterange,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
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
                $(".total-keywords-hit-rate").text(
                    response.data.keywords_hits.length > 0
                        ? response.data.keywords_hits.length
                        : 0
                );
                $(".total-missed-keywords").text(
                    response.data.keywords_missed > 0
                        ? response.data.keywords_missed
                        : 0
                );

                $("[data-trigger='keywords-details-modal']").attr(
                    "data-keyword-hits",
                    JSON.stringify(response.data.keywords_hits)
                );
                $("[data-trigger='keywords-details-modal']").attr(
                    "data-total-communications",
                    response.data.total_communications
                );
                $(".total-keywords-hit-rate-percentage").text(
                    response.data.keywords_missed
                );

                $(".total-keywords-hit-rate").text(
                    response.data.overall_keywords_hit_rate > 0
                        ? response.data.overall_keywords_hit_rate
                        : 0
                );
            }
        },
    }).done(function (response) {
        const data = Object.entries(response.data.total_calls_by_hour).map(
            function (item) {
                return {
                    y: item[0] + ":00",
                    a: item[1],
                };
            }
        );
        console.log(data);
        if (data.length > 0) {
            $("#morris-bar-chart").removeClass("d-none");
            $(".morris-bar-chart-placeholder")
                .removeClass("d-none")
                .addClass("d-none");

            $("#morris-bar-chart").empty();
            Morris.Bar({
                element: "morris-bar-chart",
                data: data,
                xkey: "y",
                ykeys: ["a"],
                labels: ["Calls"],

                barColors: ["#FC6C8E"],
                hideHover: "auto",
                resize: true,
                width: "100%",
            });
        } else {
            $("#morris-bar-chart").removeClass("d-none").addClass("d-none");
            $(".morris-bar-chart-placeholder").removeClass("d-none");
        }
    });
}
