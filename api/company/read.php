<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/25/2017
 * Time: 5:51 PM
 */


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/company.php';

$database = new Database();
$db = $database->getConnection();


$company = new Company($db);

//query products
$stmt = $company->read();

$num = $stmt->rowCount();
//echo $num;
//check if more than 0 record found
if ($num > 0)
{
    $items_arr = array();
    $items_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop

    Company::toArray($stmt, $items_arr);
    /*
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

        $visitor_item = array(
            "id" => $id,
            "login" => $login,
            "pswrd_hash" => $pswrd_hash,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "ticket_number" => $ticket_number,
            "image" => $image
        );
        array_push($visitor_arr["records"], $visitor_item);

    }
    */
    echo json_encode($items_arr);
}
else
{
    echoMessage('There is no visitors!');
}
