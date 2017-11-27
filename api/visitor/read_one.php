<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/visitor.php';

$database = new Database();
$db = $database->getConnection();

$visitor = new Visitor($db);

$visitor->id = isset($_GET['id']) ? $_GET['id'] : die();

$visitor->readOne();

$visitor_arr = array(
            "id" => $visitor->id,
            "login" => $visitor->login,
            "pswrd_hash" => $visitor->password_hash,
            "first_name" => $visitor->first_name,
            "last_name" => $visitor->last_name,
            "ticket_number" => $visitor->ticket_number,
            "image" => $visitor->image
);
//var_dump($visitor);
//var_dump($visitor_arr);
print_r(json_encode($visitor_arr));