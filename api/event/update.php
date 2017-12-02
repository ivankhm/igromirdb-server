<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 10:47 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/event.php';

$database = new Database();
$db = $database->getConnection();

$stand = new StandEvent($db);

$data = json_decode(file_get_contents("php://input"));

$stand->id = $data->id;
$stand->event_time = $data->event_time;
$stand->stand_id = $data->stand_id;
$stand->description = $data->description;
$stand->title = $data->title;

if ($stand->update())
{
    echoMessage("Stand event updated!");
}
else
{
    echoMessage("Unable to update stand event.");
}
