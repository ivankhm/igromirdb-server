<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/2/2017
 * Time: 12:00 AM
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/root.php';

$database = new Database();
$db = $database->getConnection();

$stand = new Root($db);

$stand->id = isset($_GET['id']) ? $_GET['id'] : die();

$stand->readOne();

$stand_arr = array(
    "id" => $stand->id,
    "visitor_id" => $stand->visitor_id,
    "event_id" => $stand->event_id,

);

print_r(json_encode($stand_arr)); //????