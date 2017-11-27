<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/27/2017
 * Time: 9:17 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/stand.php';

$database = new Database();
$db = $database->getConnection();


$visitor = new Stand($db);

//query products
$stmt = $visitor->read();

$num = $stmt->rowCount();
//echo $num;
//check if more than 0 record found
if ($num > 0)
{
    $visitor_arr = array();
    $visitor_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop

    Stand::toArray($stmt, $visitor_arr);
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
    echo json_encode($visitor_arr);
}
else {
    echoMessage("No Stands");
}
