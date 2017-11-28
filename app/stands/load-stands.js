

function showStands()
{
    $.getJSON("http://localhost/igromirdb-server/api/stand/read.php", function(data){
        
        var obj = JSON.parse(sessionStorage.getItem('user'));
        //while
        var pageHTML = "<h1>"+obj.userName+"</h1>";
        
        pageHTML += "<div class='row'>";
        
        $.each(data.records, function(key, val){
            pageHTML += "<div class='column'>"
                pageHTML+="<div class='stand-content' id='stand-preview-"+val.id+"'>"
                    pageHTML+="<img id='stand-image' style='width: 200px' src='"+val.image+"'>"
                    pageHTML+= "<p><label id='stand-title'>"+val.title+"</label></p>"
                    pageHTML+="<button >Take stand</button>";        
            pageHTML+="</div></div>"
        });
        pageHTML+="</div>";
        $("#page-content").html(pageHTML);
        changePageTitle("Stands");
        
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
                        'login' : result.login,
                        'userName' : result.company_name,
                        'isCompany' : isCompany
                    };
                }
                else
                {
                    user =  
                    {
                        'login' : result.login,
                        'userName' : result.first_name+" "+result.second_name,
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