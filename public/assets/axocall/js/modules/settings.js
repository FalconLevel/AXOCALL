$(document).ready(function () {
    _fetch_tags();

    init_actions();
    // $(".add-tag").click(function () {
    //     var tag_name = $("#tag_name").val();
    //     var tag_color = $("#tag_color").val();
    //     var url = "{{ route('executor.tags.save') }}";
    //     var request_type = "POST";
    //     var data = {
    //         tag_name: tag_name,
    //         tag_color: tag_color,
    //     };
    // });
});

function _fetch_tags() {
    ajaxRequest("/executor/tags/all", {}, "");
}

function init_actions() {
    $("[data-trigger]").off();
    $("[data-trigger]").click(function (e) {
        e.preventDefault();
        let trigger = $(this).data("trigger");
        let parentForm = $(this).closest("form");
        switch (trigger) {
            case "add-tag":
                let fields = JSON.parse(_collectFields(parentForm));
                console.log(fields);
                ajaxRequest("/executor/tags/save", fields, "");
                break;
            case "delete-tag":
                let id = $(this).data("id");
                ajaxRequest("/executor/tags/delete/" + id, {}, "");
                break;
        }
    });
}
