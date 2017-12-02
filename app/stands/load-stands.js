
function showStands()
{
    $.getJSON("http://localhost/igromirdb-server/api/stand/read.php", function(data){
        
        jQuery.get('http://localhost/igromirdb-server/app/stands/stand-modal.html', function (htmldata) {
        
        var obj = JSON.parse(sessionStorage.getItem('user'));
        //while
        var pageHTML = "<h1>Logged as "+obj.userName+"("+obj.login+")</h1>";
        
        
        pageHTML += htmldata;
        
        
        pageHTML += "<div class='row'>";
        //var obj = JSON.parse(sessionStorage.getItem('user'));
        var open_string = "";
        $.each(data.records, function(key, val){
            pageHTML += "<div class='column'>";
                pageHTML+="<div class='stand-content' id='stand-preview-"+val.id+"'>";
                    pageHTML+="<img id='stand-image' style='width: 300px' src='"+val.image+"'>";
                    pageHTML+= "<p><label id='stand-title'>"+val.title+"</label></p>";
            open_string="";
                    if (obj.isCompany) {
                        if (val.owner_id === obj.id) {
                            open_string = "Change Stand";
                        } else if (val.owner_id === null) {
                            open_string = "Take Stand";
                        }
                    } else {
                        open_string = "More info";
                    }
                    if (open_string.length !== 0)
                    {
                        pageHTML+="<button id='openModal' onclick='onChangeStand("+val.id+")'>" + open_string + "</button>";
                    }


            pageHTML+="</div></div>"
        });
        //var a = (true)?(1):(0);
        pageHTML+="</div>";
        
        $("#page-content").html(pageHTML+'<script src="app/stands/stand.js"></script>');
        changePageTitle("Stands");
        
    });
        
});
}

function setUserInfo(id, isCompany)
{
    var url_path;
    
    if (isCompany)
    {
            url_path = "http://localhost/igromirdb-server/api/company/read_one.php"
    } 
    else
    {
            url_path = "http://localhost/igromirdb-server/api/visitor/read_one.php"

    }
    
    url_path+="?id="+id;
    
    $.ajax({
            url: url_path,
            type: "GET",
            contentType: 'application/json',
            success: function (result) {
                console.log('success');
                console.log(result);
                
                var user;
                if (isCompany)
                {
                    user =  
                    {
                        'id': result.id,
                        'login' : result.login,
                        'userName' : result.company_name,
                        'isCompany' : isCompany
                    };
                }
                else
                {
                    user =  
                    {
                        'id': result.id,
                        'login' : result.login,
                        'userName' : result.first_name+" "+result.last_name,
                        'isCompany' : isCompany
                    };
                }
                
                sessionStorage.setItem('user', JSON.stringify(user));   
                showStands();
            },
            error: function (xhr, resp, text) {
                console.log('fail');
                console.log(xhr, resp, text);
            }
        });  
}

function loadEvents(id, fromDB) {
    //alert('here');
    var event_html ="<table class='table table-bordered table-hover'>";

    // creating our table heading
    event_html+="<tr>";
    event_html+="<th class='w-10-pct'>Time</th>";
    event_html+="<th class='w-30-pct'>Title</th>";
    event_html+="<th class='w-50-pct'>Description</th>";
    event_html+="<th class='w-10-pct text-align-center'>Action</th>";
    event_html+="</tr>";

    if (fromDB) {
        modalStandEvents = [];

        $.ajax({
            url: "http://localhost/igromirdb-server/api/event/read-stand-events.php?stand-id=" + id,
            dataType: 'json',
            async: false,
            success: function(data) {
                $.each(data.records, function (key, val) {
                    //alert(val.title);
                    modalStandEvents.push(
                        {
                            'event_time': val.event_time,
                            'title': val.title,
                            'description': val.description,
                            'stand_id': id,
                            'isNew' : false
                        }
                    );
                });
                //alert(modalStandEvents[0].title);
            }
        });
    }
    //alert(modalStandEvents[0].title);
    $.each(modalStandEvents, function (key, val) {
        //lert(val.title);
        // creating new table row per record
        event_html += "<tr>";

        event_html += "<td>" + val.event_time + "</td>";
        event_html += "<td>" + val.title + "</td>";
        event_html += "<td>" + val.description + "</td>";

        // 'action' buttons
        event_html += "<td>";

        // edit button
        event_html += "<button class='btn btn-info m-r-10px update-product-button' data-id='" + val.id + "'>";
        event_html += "<span class='glyphicon glyphicon-edit'></span> Edit";
        event_html += "</button>";

        // delete button
        event_html += "<button class='btn btn-danger delete-product-button' data-id='" + val.id + "'>";
        event_html += "<span class='glyphicon glyphicon-remove'></span> Delete";
        event_html += "</button>";
        event_html += "</td>";

        event_html += "</tr>";
    });

    // end table
    event_html+="</table>";

    $("#stand-events").html(event_html);
}