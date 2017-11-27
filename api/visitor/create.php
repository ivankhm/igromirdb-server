<?php
// required headers
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

$visitor->image = $data->image;
$visitor->first_name = $data->first_name;
$visitor->last_name = $data->last_name;
$visitor->login = $data->login;
$visitor->password_hash = password_hash($data->password, PASSWORD_DEFAULT);
$visitor->ticket_number = $data->ticket_number;

/*
$visitor->fisrt_name = $_GET['first_name'];
$visitor->last_name = $_GET['last_name'];
$visitor->login = $_GET['login'];
$visitor->password_hash = password_hash($_GET['password']);
$visitor->ticket_number = $_GET['ticket_number'];
*/

//create visitor
if ($visitor->create())
{
    echoMessage('Visitor was created!');
}
else
{
    echoMessage('Unable create visitor!');
}

/*

http://localhost/igromirdb-server/api/visitor/create.php?login=%27kostan228%27,first_name=%27Yiri%27,last_name=%27Kostikov%27,password=%27samosval%27,ticket_number=%27spescomsosizhopy%27

*/