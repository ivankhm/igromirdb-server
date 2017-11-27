<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/database.php';
include_once '../objects/visitor.php';

$database = new Database();
$db = $database->getConnection();

$visitor = new Visitor($db);

$data = json_decode(file_get_contents("php://input"));

$visitor->id = $data->id;

$visitor->image = $data->image;
$visitor->first_name = $data->first_name;
$visitor->last_name = $data->last_name;
$visitor->login = $data->login;
$visitor->password_hash = password_hash($data->password, PASSWORD_DEFAULT);
$visitor->ticket_number = $data->ticket_number;

if ($visitor->update())
{
    echo '{';
        echo '"message": "Visitor was updated."';
    echo '}';
}
 
// if unable to update the product, tell the user
else{
    echo '{';
        echo '"message": "Unable to update visitor."';
    echo '}';
}