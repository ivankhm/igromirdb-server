<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/25/2017
 * Time: 10:54 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/visitor.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

$visitor = new Visitor($db);

$visitor->login = $data->login;



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

    if (password_verify($data->password, $row['pswrd_hash'])) {
        $result["result"] = true;
        $result["id"] = $row["id"];
    }
}

echo json_encode($result);
