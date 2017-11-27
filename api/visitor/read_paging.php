<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/visitor.php';

// utilities
$utilities = new Utilities();
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$visitor = new Visitor($db);

$stmt = $visitor->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

if ($num > 0)
{
    $visitors_arr=array();
    $visitors_arr["records"]=array();
    $visitors_arr["paging"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        
        extract($row);
        
        $visitor_item = array(
            "id" => $id,
            "login" => $login,
            "pswrd_hash" => $pswrd_hash,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "ticket_number" => $ticket_number,
            "image" => $image
        );
        //var_dump($visitor_item);
        
        
        array_push($visitors_arr["records"], $visitor_item);
    }
    
    $total_rows = $visitor->count();
    $page_url = "{$home_url}visitor/read_paging.php?";
    
    //echo "page = $page, total_rows = $total_rows, records_per_page = $records_per_page<br/>";
    
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $visitors_arr["paging"]=$paging;
    
    echo json_encode($visitors_arr);
}
else 
{
    echo json_encode(
        array("message" => "No products found.")
    );
}