<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/25/2017
 * Time: 10:54 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


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

//$data = json_decode(file_get_contents("php://input"));

//$visitor->login = $data->login;
//$visitor->password_hash = password_hash($data->password, PASSWORD_DEFAULT);
$visitor->login = $_GET['login'];
$visitor->password_hash = password_hash($_GET['password'], PASSWORD_DEFAULT);

//echo $_GET['password'];

//var_dump($visitor);

$stmt = $visitor->login();

$num = $stmt->rowCount();

$result = array (
    "result" => false,
    "id" => null
);
//echo 1;
if ($num > 0)
{
    //echo 2;
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //var_dump($row);

    if (password_verify($_GET['password'], $row['pswrd_hash'])) {
        $result["result"] = true;
        $result["id"] = $row["id"];
    }
}

echo json_encode($result);
