<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 11:59 PM
 */

// required headers
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

// get posted data
/*
$data = json_decode('{
            "login": "pesok228",
            "password": "samosval",
            "first_name": "Yiri",
            "last_name": "Kostikov",
            "ticket_number": "otchislensosixuy",
            "image": "/a/a/a"

            }');
*/

//var_dump($data);
//return;
//var_dump(file_get_contents("php://input"));

$data = json_decode(file_get_contents("php://input"));

$root->event_id = $data->event_id;
$root->visitor_id = $data->visitor_id;



/*
$visitor->fisrt_name = $_GET['first_name'];
$visitor->last_name = $_GET['last_name'];
$visitor->login = $_GET['login'];
$visitor->password_hash = password_hash($_GET['password']);
$visitor->ticket_number = $_GET['ticket_number'];
*/

//create visitor
if ($root->create())
{
    echoMessage('Root Event was created!');
}
else
{
    echoMessage('Unable create a root event!');
}