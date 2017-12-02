<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 11:59 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/root.php';

$database = new Database();
$db = $database->getConnection();


$standEvent = new StandEvent($db);

$stand_id = isset($_GET['stand-id'])?($_GET['stand-id']):null;
$visitor_id = isset($_GET['visitor_id'])?($_GET['visitor_id']):null;

//query products
$stmt = $standEvent->read($stand_id, $visitor_id);

$num = $stmt->rowCount();
//echo $num;
//check if more than 0 record found
if ($num > 0)
{
    $standEvent_arr = array();
    $standEvent_arr["records"] = array();

    StandEvent::toArray($stmt, $standEvent_arr);

    echo json_encode($standEvent_arr);
}
else
{
    echoMessage("No Such Root");
}
