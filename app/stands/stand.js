var modal = document.getElementById('updateStandModal');
var watch_modal = document.getElementById('watchStandModal');
var modalStandEvents = [];
var visitorRoots = [];
var tempIndex = -1;
//var btn = document.getElementById('openModal');
        
//var span = document.getElementsByClassName('close')[0];

//var submitBtn = document.getElementsByClassName('update-stand')[0];


function onChangeStand(id){
    //alert('!!!!!!!!!');
    //modal = document.getElementById('updateStandModal');
    //var obj = JSON.parse(sessionStorage.getItem('user'));
    //alert('here');
    $.getJSON("http://localhost/igromirdb-server/api/stand/read-one.php?id="+id, function(data){
        document.getElementsByName('title')[0].value = data.title;
        document.getElementsByName('description')[0].value = data.description;
        document.getElementsByName('hiden_image')[0].value = data.image;
        alert(data.owner_id);
        document.getElementById('update-stand-submit').textContent = ((data.owner_id === null)?("Take Stand"):("Update Stand"));
        document.getElementById("crutch-id").value = data.id;
        document.getElementById("crutch-owner_id").value = data.owner_id;
    });
    //alert(id);
    loadEvents(id, true, true);
    modal.style.display = "block";
}

function onWatchStand(id) {
    $.getJSON("http://localhost/igromirdb-server/api/stand/read-one.php?id="+id, function(data){

        document.getElementById('watch-title').textContent = data.title;
        document.getElementById('watch-img').src = data.image;

        $('#watch-description').html(data.description);

        document.getElementById("crutch-id").value = data.id;
        document.getElementById("crutch-owner_id").value = data.owner_id;
    });
    //alert(id);

    loadEvents(id, true, false);
    watch_modal.style.display = "block";
}


/*
span.onclick = function()
{
    //modal = document.getElementById('updateStandModal');
    modal.style.display = "none";
};
*/
window.onclick = function(event)
{
    //modal = document.getElementById('updateStandModal');
    if (event.target === modal)
    {
        modal.style.display = "none";
    }
    if (event.target === watch_modal)
    {
        updateRoots();
        watch_modal.style.display = "none";
    }
};

function addEventToTemp() {
    var id = document.getElementById("crutch-id").value;
    tempIndex -=1;
    var data =
        {
            'id' : tempIndex.toString(),
            'event_time': document.getElementsByName("event_time")[0].value,
            'title': document.getElementsByName('event_title')[0].value,
            'description': document.getElementsByName('event_description')[0].value,
            'stand_id': id,
            'isDeleted': false,
            'isUpdated': false,
            'isNew' : true
        };

    modalStandEvents.push(data);

    loadEvents(id, false, true);

}

$(document).on('click', '.delete-event-button', function () {
    var event_id = $(this).attr('data-id');
    //var value =
    var stand_id = modalStandEvents.filter(function (t) { return t.id === event_id; })[0].stand_id;

    modalStandEvents
        .filter(function (t) {return t.id === event_id;})[0]
        .isDeleted = true;
    loadEvents(stand_id, false, true);
});

$(document).on('click', '.change-root-button', function () {
    var event_id = $(this).attr('data-id');
    //var stand_id = modalStandEvents.filter(function (t) { return t.id === event_id; })[0].stand_id;
    var obj = JSON.parse(sessionStorage.getItem('user'));

    var event_filter = visitorRoots.filter(function (t) { return t.event_id === event_id; });

    $(this).toggleClass('not-in-root');

    if ($(this).hasClass('not-in-root'))
    {
        /*
        visitorRoots.splice(visitorRoots.indexOf(
            event_filter[0]
        ), 1);
        */
        event_filter[0].isToDelete = true;
        $(this).html('Add To Root');
    } else {
        //если нет еще

        if (event_filter.length === 0) {

            tempIndex -= 1;
            visitorRoots.push(
                {
                    'id': tempIndex.toString(),
                    'event_id': event_id,
                    'visitor_id': obj.id,
                    'isNew': true,
                    'isToDelete': false
                }
            );

        } else
        {
            event_filter[0].isToDelete = false;
        }
        $(this).html('Remove From Root');
    }
});
