<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 9:15 PM
 */

// required headers
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

$stand->event_time = $data->event_time;
$stand->title = $data->title;
$stand->description = $data->description;
$stand->stand_id = $data->stand_id;


if ($stand->create())
{
    echoMessage('Stand Event was created!');
}
else
{
    echoMessage('Unable create a stand event!');
}