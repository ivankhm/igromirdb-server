<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/27/2017
 * Time: 10:04 PM
 */

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

$stand->id = $data->id;
$stand->image = $data->image;
$stand->owner_id = $data->owner_id;
$stand->description = $data->description;
$stand->title = $data->title;


if ($stand->update())
{
    echoMessage("Stand updated!");
}

// if unable to update the product, tell the user
else{
    echo '{';
        echo '"message": "Unable to update stand."';
    echo '}';
}
