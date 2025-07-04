$(document).ready(function () {
    $('[data-trigger="logout"]').on("click", function () {
        ajaxRequest("/executor/account/logout", "", "");
    });
});

function ajaxRequest(sUrl = "", sData = "", sLoadParent = "") {
    $.ajax({
        url: sUrl,
        type: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
        data: sData,
        beforeSend: function () {},
        success: function (result) {
            console.log(result);
            eval(result.js);
        },
        error: function (e) {
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
            $(".div-loader").show();
        },
        success: function (result) {
            $(".div-loader").hide();
            console.log(result);
            eval(result.js);
        },
        error: function (e) {
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
