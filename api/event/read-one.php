<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 9:15 PM
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/event.php';

$database = new Database();
$db = $database->getConnection();

$stand = new StandEvent($db);

$stand->id = isset($_GET['id']) ? $_GET['id'] : die();

$stand->readOne();

$stand_arr = array(
    "id" => $stand->id,
    "title" => $stand->title,
    "description" => $stand->description,

);
//var_dump($visitor);
//var_dump($visitor_arr);
print_r(json_encode($stand_arr));