var modal = document.getElementById('updateStandModal');
        
var btn = document.getElementById('openModal');
        
var span = document.getElementsByClassName('close')[0];
        
function onChangeStand(id){
    //alert('!!!!!!!!!');
    //modal = document.getElementById('updateStandModal');
    $.getJSON("http://localhost/igromirdb-server/api/stand/read-one.php?id="+id, function(data){
        document.getElementsByName('title')[0].value = data.title;
        document.getElementsByName('description')[0].value = data.description;
        document.getElementsByName('image')[0].value = data.image;
        
    });
    modal.style.display = "block";   
}  
span.onclick = function()
{
    //modal = document.getElementById('updateStandModal');
    modal.style.display = "none";
}
        
window.onclick = function(event)
{
    //modal = document.getElementById('updateStandModal');
    if (event.target == modal)
    {
        modal.style.display = "none";
    }
}

$(document).on('submit', "#update-stand-form", 
function()
{
    alert("SUBMIT!");
    
    
    
});