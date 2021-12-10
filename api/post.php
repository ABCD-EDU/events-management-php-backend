<?php
    require_once '../config/database.php';

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

    // Instantiate DB & connect
    // $database = new Database();
    // $db = $database->connect();

    $data = json_decode(file_get_contents("php://input"));

    echo json_encode($data);
