<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/2/2017
 * Time: 12:00 AM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/root.php';

$database = new Database();
$db = $database->getConnection();

$root = new Root($db);

$data = json_decode(file_get_contents("php://input"));

$root->id = $data->id;

if($root->delete())
{
    echoMessage("Root was deleted.");
}
else
{
    echoMessage("Unable to delete a Root");
}