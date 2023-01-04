<?php

include 'function.php';
check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {
    case 'upload_document':
        $error = [];
        if (!isset($_POST['first_name']) || empty($_POST['first_name'])) $error['first_name'] = "First name should not be empty";
        if (!isset($_POST['last_name']) || empty($_POST['last_name'])) $error['last_name'] = "Last name should not be empty";
        if (!isset($_POST['age']) || empty($_POST['age'])) $error['age'] = "Age should not be empty";
        if (!isset($_POST['phone_no']) || empty($_POST['phone_no'])) $error['phone_no'] = "Phone no should not be empty";
        else if(strlen($_POST['phone_no']) != 10) $error['phone_no'] = "Phone no should have 10 digit";
        if (!isset($_FILES['document'])) $error['document'] = "Document is required";
        else {
            $valid_file = valid_file($_FILES['document'], ["pdf", "image"]);
            if ($valid_file != 1) $error['document'] = "Please upload valid document";
        }
        if (sizeof($error) > 0) sendData(false, $error);

        $file_basename = get_file_name($_FILES['document']);
        $uploadfile = UPLOAD_DOCUMENT_PATH . $file_basename;
        try {
            if (!move_uploaded_file($_FILES['document']['tmp_name'], $uploadfile)) {
                sendData(false, "Document uploaded");
            }
        } catch (Exception $err) {
            sendData(false, "Document uploaded");
        }

        $first_name = sql_prevent($conn, xss_prevent($_POST['first_name']));
        $last_name = sql_prevent($conn, xss_prevent($_POST['last_name']));
        $age = sql_prevent($conn, xss_prevent($_POST['age']));
        $phone_no = sql_prevent($conn, xss_prevent($_POST['phone_no']));

        $create_query = "INSERT into document (first_name, last_name, age, phone_no, document, created_at) value ('$first_name', '$last_name', '$age', '$phone_no', '$file_basename', current_timestamp())";
        if (query_create($conn, $create_query)) sendData(true, "Document upoaded");
        else sendData(false, "Document not able to upload");

        sendData(true, "Document uploaded");
        break;

    default:
        sendData(false, "Method not found");
        break;
}
