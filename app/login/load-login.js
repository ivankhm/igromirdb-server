

$(document).ready(function () {
    loadLoginPage();

});

function loadLoginPage() {
    jQuery.get('http://localhost/igromirdb-server/app/login/login.html', function (data) {
        $("#page-content").html(data+'<script src="app/login/register-form.js"></script>');
    });
    changePageTitle("Authorisation");
}