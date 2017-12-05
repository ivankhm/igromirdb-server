function loadProfile() {
    var obj = JSON.parse(sessionStorage.getItem('user'));
    var user_info_url = "http://localhost/igromirdb-server/api/visitor/read-one.php?id=";
    var profile_content_url = "http://localhost/igromirdb-server/api/root/read.php?visitor-id=";

    var content_title = "Your Root";


    if (obj.isCompany)
    {
        user_info_url = "http://localhost/igromirdb-server/api/company/read-one.php?id=";
        content_title = "Your Stands";
    }

    $.getJSON(user_info_url+obj.id, function(data) {
        jQuery.get('http://localhost/igromirdb-server/app/profile/profile.html', function (htmldata) {
            //read user from db

            $('#page-content').html(htmldata);
            changePageTitle('Profile');
            $('#profile-image').html("<image src='" + data.image+"'>");
            $('#profile-login').html(data.login);
            $('#profile-name').html(obj.userName);
            $('#profile-description').html(data.userName);
            $('#profile-content-title').html(content_title);

            $.getJSON(profile_content_url+obj.id, function (profile_content_data) {

            });
            $('#profile-list-content').html("<image src='" + data.image+"'>");


        });
    });
}