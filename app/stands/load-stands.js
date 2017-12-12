
function showStands()
{
    $.getJSON("http://localhost/igromirdb-server/api/stand/read.php", function(data){
        
        jQuery.get('http://localhost/igromirdb-server/app/stands/stand-modal.html', function (htmldata) {
        jQuery.get('http://localhost/igromirdb-server/app/stands/watch-stand-modal.html', function (watch_htmldata) {


        var obj = JSON.parse(sessionStorage.getItem('user'));
        //while
        var pageHTML = "<h1>Logged as "+obj.userName+"("+obj.login+")</h1>";


        
        pageHTML += htmldata;
        pageHTML += watch_htmldata;
        
        pageHTML += "<div class='row'>";
        //var obj = JSON.parse(sessionStorage.getItem('user'));
        var open_string = "";
        $.each(data.records, function(key, val){
            pageHTML += "<div class='column'>";
                pageHTML+="<div class='stand-content' id='stand-preview-"+val.id+"'>";
                    pageHTML+="<img id='stand-image' style='height: 300px' src='"+val.image+"'>";
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

                        pageHTML+="<button id='openModal' onclick=";
                        if (obj.isCompany)
                        {
                            pageHTML+="'onChangeStand("+val.id+")'>";
                        } else
                        {
                            pageHTML+="'onWatchStand("+val.id+")'>";
                        }
                        pageHTML+=open_string + "</button>";

                    }


            pageHTML+="</div></div>"
        });
        //var a = (true)?(1):(0);
        pageHTML+="</div>";
        
        $("#page-content").html(pageHTML+'<script src="app/stands/stand.js"></script>');
        changePageTitle("Stands");
        });
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

function updateRoots() {
    var data;
    var url_path;//= "http://localhost/igromirdb-server/api/root/delete.php";
    $.each(visitorRoots, function (key, val) {
        if (val.isNew || val.isToDelete) {
            if (!val.isNew) {
                if (val.isToDelete) {
                    //delete from db
                    url_path = "http://localhost/igromirdb-server/api/root/delete.php";
                }
            } else {
                if (!val.isToDelete) {
                    //add to db
                    url_path = "http://localhost/igromirdb-server/api/root/create.php";
                }
            }
            data = JSON.stringify(val);
            $.ajax({
                url: url_path,
                type: "POST",
                contentType: 'application/json',
                data: data,
                success: function (result) {
                    console.log('success');
                    console.log(result);
                },
                error: function (xhr, resp, text) {
                    console.log('fail');
                    console.log(xhr, resp, text);
                }

            });
        }
    });

}


function getRoots(visitor_id, stand_id) {

    $.ajax({
        url: "http://localhost/igromirdb-server/api/root/read.php?stand-id=" + stand_id + "&visitor_id=" + visitor_id,
        dataType: 'json',
        async: false,
        success: function (data) {
            visitorRoots = [];
            $.each(data.records, function (key, val) {
                visitorRoots.push(
                    {
                        'id': val.id,
                        'event_id': val.event_id,
                        'visitor_id': val.visitor_id,
                        'isNew': false,
                        'isToDelete': false
                    }
                );
            });
        }
    });
}

function loadEvents(id, fromDB, isCompany) {
    //alert('here');
    var obj = JSON.parse(sessionStorage.getItem('user'));
    
    getRoots(obj.id, id);
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
                            'id' : val.id,
                            'event_time': val.event_time,
                            'title': val.title,
                            'description': val.description,
                            'stand_id': id,
                            'isDeleted': false,
                            'isUpdated': false,
                            'isNew' : false
                        }
                    );
                });
                //alert(modalStandEvents[0].title);
            }
        });
    }

    $.each(modalStandEvents, function (key, val) {
        if (!val.isDeleted) {
            // creating new table row per record
            event_html += "<tr>";

           // event_html += "<td>" + val.event_time + "</td>";
            //event_html += "<td>" + val.title + "</td>";
            //event_html += "<td>" + val.description + "</td>";


            event_html += "<td><div id='event_time_"+val.id+"'>" + val.event_time + "</div></td>";
            event_html += "<td><div id='title_"+val.id+"'>" + val.title + "</div></td>";
            event_html += "<td><div id='description_"+val.id+"'>" + val.description + "</div></td>";

            // 'action' buttons
            event_html += "<td>";
            if (isCompany)
            {
                event_html += "<button type='button' class='btn btn-info m-r-10px update-event-button edit-button' data-id='" + val.id + "'>";
                //event_html += "<span class='glyphicon glyphicon-edit'></span>Edit";
                event_html += "Edit";
                event_html += "</button>";



                // delete button
                event_html += "<button type='button' class='btn btn-danger delete-event-button' data-id='" + val.id + "'>";
                event_html += "<span class='glyphicon glyphicon-remove'></span>Delete";
                event_html += "</button>";
            }
            else {

                if (visitorRoots.filter(function (t) { return t.event_id === val.id; }).length === 0)
                {
                    event_html += "<button type='button' class='btn btn-ifo change-root-button not-in-root' data-id='" + val.id + "'>Add To Root</button>";
                }
                else
                {
                    event_html += "<button type='button' class='btn btn-ifo change-root-button' data-id='" + val.id + "'>Remove From Root</button>";
                }
            }
            // edit button

            event_html += "</td>";

            event_html += "</tr>";
        }
    });

    // end table
    event_html+="</table>";
    if (isCompany) {
        $("#stand-events").html(event_html);
    } else {
        $("#watch-stand-events").html(event_html);
    }

}


function submitEvents() {
    var url_path;// = "http://localhost/igromirdb-server/api/event/create.php";
    var form_data;
    var isNeedToUpdate = false;
    $.each(modalStandEvents, function (key, val) {
        isNeedToUpdate = false;
        if (val.isDeleted && !val.isNew)
        {
            console.log(val.id + ' '+ val.isDeleted + val.isNew);
            url_path = "http://localhost/igromirdb-server/api/event/delete.php";
            isNeedToUpdate = true;
        }
        if (val.isNew && !val.isDeleted)
        {
            console.log(val.id + ' '+ val.isDeleted + val.isNew);
            url_path = "http://localhost/igromirdb-server/api/event/create.php";
            isNeedToUpdate = true;
        }
        if (val.isUpdated && !val.isNew && !val.isDeleted)
        {
            url_path = "http://localhost/igromirdb-server/api/event/update.php";
            isNeedToUpdate = true;
        }


        if (isNeedToUpdate) {
            form_data = JSON.stringify(val);
            $.ajax({
                    url: url_path,
                    type: "POST",
                    contentType: 'application/json',
                    data: form_data,
                    success: function (result) {
                        console.log('success');
                        console.log(result);
                        //alert(result.message);
                        //modal.style.display = "none";
                        //showStands();
                        //loadEvents(id);
                    },
                    error: function (xhr, resp, text) {
                        console.log('fail');
                        console.log(xhr, resp, text);
                    }
                }
            );
        }

    });
}

$(document).on('click', '.update-event-button', function(){
    var event_id = $(this).attr('data-id');

    var val = modalStandEvents.filter(function (t) { return t.id === event_id; })[0];

    $(this).toggleClass('edit-button');

    if ($(this).hasClass('edit-button'))
    {
        var event_time = document.getElementsByName("changed-event-time-"+val.id)[0];
        var title = document.getElementsByName('changed-title-'+val.id)[0];
        var description = document.getElementsByName('changed-description-'+val.id)[0];

        //val.event_time = event_time.value;

        val.title = title.value;
        val.description = description.value;
        val.isUpdated = true;

        $(this).html("Edit");
        $('#event-time_'+event_id).html(val.event_time);
        $('#title_'+event_id).html(val.title);
        $('#description_'+event_id).html(val.description);
    } else
    {
        $(this).html("Save");
        $('#event-time_'+event_id).html("<input name='changed-event-time-"+val.id+"' type='time' value='"+val.event_time+"' />");
        $('#title_'+event_id).html("<input name='changed-title-"+val.id+"' type='text' value='"+val.title+"' />");
        $('#description_'+event_id).html("<textarea name='changed-description-"+val.id+"' >" + val.description + "</textarea>");
    }
});






$(document).on('submit', "#update-stand-form",
    function()
    {
        var owner_id = document.getElementById("crutch-owner_id");
        //alert(owner_id.value);
        var obj = JSON.parse(sessionStorage.getItem('user'));
        if (owner_id.value === "")
        {
            owner_id.value = obj.id;
        } else
        if (owner_id.value !== obj.id)
        {
            alert("You can't update this stand!");
            return false;
        }

        var form_obj = $(this).serializeObject();
        var url_path = "http://localhost/igromirdb-server/api/stand/update.php";
        
        //send image first
    
        var file_data = $('#stand-image-file').prop('files')[0];
        
        if (typeof file_data !== 'undefined') 
        {
        var file_form_data = new FormData();
        file_form_data.append('file', file_data);
        //alert(file_form_data);
        $.ajax(
        {
            url: "http://localhost/igromirdb-server/api/fileUpload/upload-file.php",
            type: "POST",
            cache: false,
            processData: false,
            contentType: false,
            data: file_form_data,
            async: false,
            success:function(result)
            {
                if (result !== "error")
                {
                    form_obj.image = result;
                }
            },
                error: function (xhr, resp, text) {
                    console.log('fail');
                    console.log(xhr, resp, text);
                }
        }
        );
        } else 
            {
                form_obj.image = form_obj.hiden_image;
            }
        
        var form_data = JSON.stringify(form_obj);
        $.ajax({
                url: url_path,
                type: "POST",
                contentType: 'application/json',
                data: form_data,
                success: function (result) {
                    console.log('success');
                    console.log(result);
                    submitEvents();
                    //alert(result.message);
                    modal.style.display = "none";
                    showStands();
                },
                error: function (xhr, resp, text) {
                    console.log('fail');
                    console.log(xhr, resp, text);
                }
            }
        );
        return false;
    });