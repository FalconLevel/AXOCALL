$(document).ready(function () {
    $('[data-trigger="login-submit"]').on("click", function (e) {
        e.preventDefault();
        let parent = $(this).parents("form");
        let formData = JSON.parse(_collectFields(parent));
        console.log(formData);
        ajaxRequest("/executor/account/login", formData, "");
    });
});
