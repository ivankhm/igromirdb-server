<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/event.php';

$database = new Database();
$db = $database->getConnection();

$stanEvent = new StandEvent($db);

$data = json_decode(file_get_contents("php://input"));

$stanEvent->id = $data->id;

if($stanEvent->delete())
{
    echoMessage("Stand Event was deleted.");
}
else
{
    echoMessage("Unable to delete Stand Event");
}