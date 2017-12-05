<?php
/*
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
*/
include_once '../config/database.php';

//var_dump($_FILES);



$fileId = 'file';

$target_dir = "../../app/resources/";
$file_name = basename($_FILES[$fileId]["name"]);
$target_file = $target_dir.$file_name;

//$uploadOk = 1;

//$imageFileType = pathinfo($target_file, PATHINFO_EXTENTION);

$check = getimagesize($_FILES[$fileId]["tmp_name"]);

if (!$check)
{
    echoMessage("File is not an image!");
    return;
}

if(move_uploaded_file($_FILES[$fileId]["tmp_name"], $target_file))
{
    echo "http://localhost/igromirdb-server/app/resources/".$file_name;
}
else
{
    echoMessage("error");
}
