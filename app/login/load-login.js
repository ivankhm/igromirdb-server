$(document).ready(function () {
    var obj = JSON.parse(sessionStorage.getItem('user'));
    if (obj === null)
        {
            loadLoginPage();
        }
    else
        {
            showStands();
        }
    //showStands();

});

function loadLoginPage() {
    jQuery.get('http://localhost/igromirdb-server/app/login/login.html', function (data) {
        $("#page-content").html(data+'<script src="app/login/register-form.js"></script>');
    });
    changePageTitle("Authorisation");
}