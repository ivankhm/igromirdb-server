<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 9:15 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/event.php';

$database = new Database();
$db = $database->getConnection();


$standEvent = new StandEvent($db);

//query products
$stmt = $standEvent->read();

$num = $stmt->rowCount();
//echo $num;
//check if more than 0 record found
if ($num > 0)
{
    $standEvent_arr = array();
    $standEvent_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop

    StandEvent::toArray($stmt, $standEvent_arr);
    /*
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
    */
    echo json_encode($standEvent_arr);
}
else {
    echoMessage("No Stand Events");
}
