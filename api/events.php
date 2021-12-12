<?php
    // get all events organizer is a part of
    require_once '../config/database.php';
    require_once '../util/auth.php';
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
    
    // validate request
    $headers = apache_request_headers();
    if (!checkKey($headers)) {
        echo "Invalid API key";
        return;
    }

    $userId = json_decode($_GET["user_id"]);

    $database = new Database();
    $db = $database->connect();

    $data = $db->prepare("SELECT * from event inner join admin_events on 
            event.event_id = admin_events.event_id where admin_events.user_id = :id");

    $data->bindParam(":id", $userId);

    $data->execute();
    $toReturn = $data->fetchAll();

    echo json_encode($toReturn);