<?php 
include_once "dbconnect.php";
include 'query.php';

function xss_prevent($value)
{
    return strip_tags(htmlspecialchars($value));
}

function sql_prevent($conn, $value)
{
    return mysqli_real_escape_string($conn, $value);
}

function check_server()
{
    if ($_SERVER['SERVER_NAME'] != constant("SERVER_NAME")) sendData(false, "Bad request");
    return true;
}

function get_file_name($file){
    $file_path = pathinfo($file['name']);
    return $file_path["filename"]."-".time().".".$file_path['extension'];
}

function valid_file($file, $valid_ext)
{
    $fileType = false;
    $allow_ext = [
        "pdf" => ["application/pdf"],
        "image" => ["image/jpeg", "image/jpg", "image/png"],
    ];
    if (($file['size'] / 1024 / 1024) > 10) return "File size is to large";
    foreach ($valid_ext as $ext) {
        if (in_array($file['type'], $allow_ext[$ext])) $fileType = true;
    }
    if (!$fileType) return "Invalid file type";
    return true;
}

function check_method($method)
{
    check_server();
    if ($_SERVER['REQUEST_METHOD'] != $method)  sendData(false, "Method not found");
    return true;
}

function sendData($success, $data)
{
    if (gettype($data) == 'string') echo json_encode(array("success" => $success, "message" => $data));
    else echo json_encode(array("success" => $success, "data" => $data));
    die;
}
