<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/visitor.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$visitor = new Visitor($db);

$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

$stmt = $visitor->search($keywords);
$num = $stmt->rowCount();

if ($num >0)
{
    //TODO обернуть в функцию
    $visitor_arr = array();
    $visitor_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    
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
        array_push($visitor_arr["records"], $visitor_item);

    }
    echo json_encode($visitor_arr);
}
else{
    echo json_encode(
        array("message" => "No products found.")
    );
}
 //TODO сука везде одинаковый вывод ошибок ыыы
   