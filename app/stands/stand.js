var modal = document.getElementById('updateStandModal');
        
//var btn = document.getElementById('openModal');
        
//var span = document.getElementsByClassName('close')[0];

//var submitBtn = document.getElementsByClassName('update-stand')[0];


function onChangeStand(id){
    //alert('!!!!!!!!!');
    //modal = document.getElementById('updateStandModal');
    //var obj = JSON.parse(sessionStorage.getItem('user'));
    $.getJSON("http://localhost/igromirdb-server/api/stand/read-one.php?id="+id, function(data){
        document.getElementsByName('title')[0].value = data.title;
        document.getElementsByName('description')[0].value = data.description;
        document.getElementsByName('image')[0].value = data.image;

        document.getElementById('update-stand-submit').textContent = ((data.owner_id === null)?("Take Stand"):("Update Stand"));
        document.getElementById("crutch-id").value = data.id;
        document.getElementById("crutch-owner_id").value = data.owner_id;
    });
    modal.style.display = "block";   
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
};

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

        var form_data = JSON.stringify($(this).serializeObject());
        var url_path = "http://localhost/igromirdb-server/api/stand/update.php";

        $.ajax({
                url: url_path,
                type: "POST",
                contentType: 'application/json',
                data: form_data,
                success: function (result) {
                    console.log('success');
                    console.log(result);
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