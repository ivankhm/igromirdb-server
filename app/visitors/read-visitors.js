$(document).ready(
    function()
    {
        showProducts();
        $(document).on('click', '.read-products-button', function(){showProducts();});
    }
);



function showProducts()
{
    $.getJSON("http://localhost/igromirdb-server/api/visitor/read.php", function(data){
        // html for listing products
        var read_visitors_html="";

        // when clicked, it will load the create product form
        read_visitors_html+="<div id='create-product' class='btn btn-primary pull-right m-b-15px create-product-button'>";
            read_visitors_html+="<span class='glyphicon glyphicon-plus'></span> Create Visitors";
        read_visitors_html+="</div>";
    
        // start table
        read_visitors_html+="<table class='table table-bordered table-hover'>";

        // creating our table heading
        read_visitors_html+="<tr>";
        read_visitors_html+="<th class='w-25-pct'>Login</th>";
        read_visitors_html+="<th class='w-10-pct'>First Name</th>";
        read_visitors_html+="<th class='w-15-pct'>Last Name</th>";
        read_visitors_html+="<th class='w-25-pct text-align-center'>Action</th>";
        read_visitors_html+="</tr>";

        // loop through returned list of data
        $.each(data.records, function(key, val) {

            // creating new table row per record
            read_visitors_html+="<tr>";

                read_visitors_html+="<td>" + val.login + "</td>";
                read_visitors_html+="<td>" + val.first_name + "</td>";
                read_visitors_html+="<td>" + val.last_name + "</td>";

                // 'action' buttons
                read_visitors_html+="<td>";
                    // read one product button
                    read_visitors_html+="<button class='btn btn-primary m-r-10px read-one-product-button' data-id='" + val.id + "'>";
                        read_visitors_html+="<span class='glyphicon glyphicon-eye-open'></span> Read";
                    read_visitors_html+="</button>";

                    // edit button
                    read_visitors_html+="<button class='btn btn-info m-r-10px update-product-button' data-id='" + val.id + "'>";
                        read_visitors_html+="<span class='glyphicon glyphicon-edit'></span> Edit";
                    read_visitors_html+="</button>";

                    // delete button
                    read_visitors_html+="<button class='btn btn-danger delete-product-button' data-id='" + val.id + "'>";
                        read_visitors_html+="<span class='glyphicon glyphicon-remove'></span> Delete";
                    read_visitors_html+="</button>";
                read_visitors_html+="</td>";

            read_visitors_html+="</tr>";
        });
 
        // end table
        read_visitors_html+="</table>";
    
        $("#page-content").html(read_visitors_html);
        changePageTitle("Read Visitors");
        
    });
    
    
    
}

