<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/stand.php';

$database = new Database();
$db = $database->getConnection();

$stand = new Stand($db);

$stand->id = isset($_GET['id']) ? $_GET['id'] : die();

$stand->readOne();

$stand_arr = array(
                "id" => $stand->id,
                "title" => $stand->title,
                "description" => $stand->description,
                "image" => $stand->image,
                "hall_id" => $stand->hall_id,
                "owner_id" => $stand->owner_id
            );
//var_dump($visitor);
//var_dump($visitor_arr);
print_r(json_encode($stand_arr));