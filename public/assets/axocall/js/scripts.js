$(document).ready(function () {
    $('[data-action="toggle-theme"]').on("click", function () {
        let icon = $(this).find("i");
        let theme = "";
        if (icon.hasClass("fa-moon")) {
            icon.removeClass("fa-moon").removeClass("text-secondary");
            icon.addClass("fa-sun").addClass("text-warning");
            theme = "dark";
        } else {
            icon.removeClass("fa-sun").removeClass("text-warning");
            icon.addClass("fa-moon").addClass("text-secondary");
            theme = "light";
        }

        ajaxRequest(
            "/executor/settings/toggle-theme",
            {
                Theme: theme,
            },
            ""
        );
    });
    $('[data-action="logout"]').on("click", function () {
        console.log("logout");
        ajaxRequest("/executor/account/logout", "", "");
    });
});

function ajaxRequest(sUrl = "", sData = "", sLoadParent = "") {
    $.ajax({
        url: sUrl,
        type: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        data: sData,
        beforeSend: function () {
            $("#system-loader").show();
        },
        success: function (result) {
            $("#system-loader").fadeOut(500);
            console.log(result);
            eval(result.js);
        },
        error: function (e) {
            $("#system-loader").fadeOut(500);
            console.log(e);

            _show_toastr(
                "error",
                "Please call system administrator!",
                "System Error"
            );
        },
    });
}

function ajaxSubmit(sUrl = "", sFormData = "", sLoadParent = "") {
    $.ajax({
        url: sUrl,
        type: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        data: sFormData,
        cache: false,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $("#system-loader").show();
        },
        success: function (result) {
            $("#system-loader").fadeOut(500);
            console.log(result);
            eval(result.js);
        },
        error: function (e) {
            $("#system-loader").fadeOut(500);
            console.log(e);
            _show_toastr(
                "error",
                "Please call system administrator!",
                "System Error"
            );
        },
    });
}

function _checkFormFields(parentForm) {
    var nCnt = 0;
    var nEmpty = 0;
    var aElements = $(parentForm).find("input, textarea, select");

    for (nCnt = 0; nCnt < aElements.length; nCnt++) {
        var sElement = aElements[nCnt];
        var sValue = $(sElement).val();
        var sData = $(sElement).attr("data");

        if ($(sElement).is(":visible")) {
            if (sData != "exclude") {
                if (sData == "req") {
                    if (sValue == "") {
                        $(sElement).addClass(" is-invalid ");
                        nEmpty++;
                    } else {
                        $(sElement).removeClass(" is-invalid ");
                    }
                }
            }
        }
    }

    if (nEmpty > 0) return false;
    else return true;
}

function _collectFields(parentForm) {
    var sJsonFields = {};
    var nCnt = 0;
    var nEmpty = 0;
    var aElements = $(parentForm).find(
        "input:not(:checkbox):not(:radio), textarea, select"
    );

    for (nCnt = 0; nCnt < aElements.length; nCnt++) {
        var sElement = aElements[nCnt];

        var sDataKey = $(sElement).attr("data-key");
        var sValue = $(sElement).val();

        if ($(sElement).is(":visible") === true) {
            if (sDataKey) {
                sJsonFields[sDataKey] = sValue;
            }
        }
    }

    return JSON.stringify(sJsonFields);
}

function _applyTheme(sTheme) {
    if (sTheme == "dark") {
        new quixSettings({
            version: "dark",
            layout: "vertical",
            navheaderBg: "color_2",
            headerBg: "color_2",
            sidebarBg: "color_2",
        });
    } else {
        new quixSettings({
            version: "light",
            layout: "vertical",
            navheaderBg: "color_1",
            headerBg: "color_1",
            sidebarBg: "color_1",
        });
    }
}
