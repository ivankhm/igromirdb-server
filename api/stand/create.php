<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/27/2017
 * Time: 9:00 PM
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/stand.php';

$database = new Database();
$db = $database->getConnection();

$stand = new Stand($db);

$data = json_decode(file_get_contents("php://input"));

$stand->image = $data->image;
$stand->title = $data->title;
$stand->description = $data->description;
$stand->hall_id = $data->hall_id;

if ($stand->create())
{
    echoMessage('Stand was created!');
}
else
{
    echoMessage('Unable create a stand!');
}
