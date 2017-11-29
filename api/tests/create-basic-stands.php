<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/28/2017
 * Time: 10:27 PM
 */

include_once '../config/database.php';
include_once '../objects/stand.php';

$database = new Database();
$db = $database->getConnection();

$stand = new Stand($db);

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
$stand->hall_id = 1;

for ($i = 4; $i < 10; $i++) {

    $stand->image = "http://localhost/igromirdb-server/app/resources/default$i.png";
    $stand->title = "Stand $i";
    $stand->description = "Add description";

    if ($stand->create())
    {
        echoMessage('Stand was created!');
    }
    else
    {
        echoMessage('Unable create a stand!');
    }

}

