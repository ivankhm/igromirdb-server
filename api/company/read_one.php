<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/company.php';

$database = new Database();
$db = $database->getConnection();

$company = new Company($db);

$company->id = isset($_GET['id']) ? $_GET['id'] : die();

$company->readOne();

$company_arr = array(
                "id" => $company->id,
                "login" => $company->login,
                "pswrd_hash" => $company->password_hash,
                "company_name" => $company->company_name,
                "company_description" => $company->company_description,
                "image" => $company->image
            );
//var_dump($visitor);
//var_dump($visitor_arr);
print_r(json_encode($company_arr));