var VisitorLabels =
    [
        ['First Name:', 'Ivan', 'first_name'],
        ['Last Name:', 'Ivanov', 'last_name'],

    ];
var CompanyLabels =
    [
        ['Company Name:', 'EA Games', 'company_name'],
        ['Description:', 'Description', 'company_description'],

    ];

var currentTab = 0;
hideAllSteps();
showTab(currentTab);
setUpVisitor();

document.getElementById("openDefault").click();

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");

    //скрываем все
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function hideAllSteps() {
    var x = document.getElementsByClassName("registerTab");
    for(var i = 0; i < x.length; i++)
    {
        x[i].style.display = "none";
    }

}

function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("registerTab");
    x[n].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n === 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n === (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    }
    else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    if (n === x.length)
    {
        document.getElementById("nextBtn").type = "submit";
    }
    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("registerTab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form... :
    if (currentTab >= x.length) {
        //...the form gets submitted: !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<<<<<поменять (не над)

        //$("reg-form").submit();
        document.getElementById("nextBtn").type = "submit";
        //document.getElementById("regForm").submit();

        return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("registerTab");
    y = x[currentTab].getElementsByTagName("input");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value === "") {
            // add an "invalid" class to the field:
            y[i].className += " invalid";
            // and set the current valid status to false:
            valid = false;
        }
    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class to the current step:
    x[n].className += " active";
}

function setUpVisitor() {
    var field = document.getElementsByName('company_name');
    document.getElementById('label-1').textContent = VisitorLabels[0][0];
    field[0].placeholder = VisitorLabels[0][1];
    field[0].name = VisitorLabels[0][2];

    field = document.getElementsByName('company_description');
    document.getElementById('label-2').textContent = VisitorLabels[1][0];
    field[0].placeholder = VisitorLabels[1][1];
    field[0].name = VisitorLabels[1][2];

    document.getElementById('label-4').style.display = "inline";
    document.getElementsByName('ticket_number')[0].style.display = "inline";
}

function setUpCompany() {
    var field = document.getElementsByName('first_name');
    document.getElementById('label-1').textContent = CompanyLabels[0][0];
    field[0].placeholder = CompanyLabels[0][1];
    field[0].name = CompanyLabels[0][2];

    field = document.getElementsByName('last_name');
    document.getElementById('label-2').textContent = CompanyLabels[1][0];
    field[0].placeholder = CompanyLabels[1][1];
    field[0].name = CompanyLabels[1][2];

    document.getElementById('label-4').style.display = "none";
    document.getElementsByName('ticket_number')[0].value = "0";
    document.getElementsByName('ticket_number')[0].style.display = "none";
}

function changeForm(element) {
    //var cb = document.getElementById('isCompany');

    if (element.checked === true)
    {
        setUpCompany();
    }
    else
    {
        setUpVisitor();
    }
}

$(document).on('submit', "#regForm", function () {

    var form_obj = $(this).serializeObject();
    var form_url;
   // alert("HELLO");
    var isCompany = document.getElementById('isCompany').checked;
    if (isCompany)
    {
        form_url = "http://localhost/igromirdb-server/api/company/create.php";
    }
    else
    {
        form_url = "http://localhost/igromirdb-server/api/visitor/create.php";
    }
    
    
    ////
    
    var file_data = $('#login-image').prop('files')[0];
        
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
     
    var form_data = JSON.stringify(form_obj);
    
    /////
    
    $.ajax({
        url: form_url,
        type: "POST",
        contentType: 'application/json',
        data: form_data,
        success: function (result) {
            console.log('success');
            console.log(result);
            //showStands();

            if (isCompany)
            {
                form_url = "http://localhost/igromirdb-server/api/company/login.php";
            }
            else
            {
                form_url = "http://localhost/igromirdb-server/api/visitor/login.php";
            }

            $.ajax({
                url: form_url,
                type: "POST",
                data: form_data,
                contentType: 'application/json',
                success: function (result) {
                    console.log('success');
                    console.log(result);
                    if (result.result === true){
                        //setUserInfo()
                        setUserInfo(result.id, isCompany);
                        //showStands();

                    }

                },
                error: function (xhr, resp, text) {
                    console.log('fail');
                    console.log(xhr, resp, text);
                }
            });

        },
        error: function (xhr, resp, text) {
            console.log('fail');
            console.log(xhr, resp, text);
        }

    });
    return false;
})


$(document).on('submit', '#login-form',
    function () {
        //alert("SUBMIT");
        //showStands();
        //var form_data = JSON.stringify($(this).serializeObject());
        var form_url;
        var form_data = JSON.stringify($(this).serializeObject());
        var isCompany = document.getElementById('is-login-company').checked;
        if (isCompany)
        {
            form_url = "http://localhost/igromirdb-server/api/company/login.php";
        }
        else 
        {
            form_url = "http://localhost/igromirdb-server/api/visitor/login.php";
        }

        //form_url+="?login="+document.getElementById("login-field").value+"&password="+document.getElementById("password-field").value;

        //TODO: convert to post method
        $.ajax({
            url: form_url,
            type: "POST",
            data: form_data,
            contentType: 'application/json',
            success: function (result) {
                console.log('success');
                console.log(result);
                if (result.result === true){
                    //setUserInfo()
                    setUserInfo(result.id, isCompany);
                    //showStands();
                   
                }
                
            },
            error: function (xhr, resp, text) {
                console.log('fail');
                console.log(xhr, resp, text);
            }
        });
    
        return false;
    }
 );