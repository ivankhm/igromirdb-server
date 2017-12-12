<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/27/2017
 * Time: 5:52 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/company.php';

$database = new Database();
$db = $database->getConnection();

$company = new Company($db);

$data = json_decode(file_get_contents("php://input"));

$company->login = $data->login;

$stmt = $company->login();

$num = $stmt->rowCount();

$result = array (
    "result" => false,
    "id" => null
);

if ($num > 0)
{
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($data->password, $row['pswrd_hash'])) {
        $result["result"] = true;
        $result["id"] = $row["id"];
    }
}

echo json_encode($result);