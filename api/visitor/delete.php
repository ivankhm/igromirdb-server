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

$vistor = Visitor($db);

$data = json_decode(file_get_contents("php://input"));

$vistor->id = $data->id;

// delete the product
if($vistor->delete()){
    echo '{';
        echo '"message": "Visitor was deleted."';
    echo '}';
}
 
// if unable to delete the product
else{
    echo '{';
        echo '"message": "Unable to delete object."';
    echo '}';
}