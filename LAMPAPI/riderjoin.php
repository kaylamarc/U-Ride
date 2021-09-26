<?php
    // get json from frontend
    $inData = getRequestInfo();

    // connect to database
    $conn = new mysqli("localhost", "root", getenv("SQL_PW"), "uride");

    // check database connection status
    if ($conn->connect_error) {
        // return DB error
        returnWithError($conn->connect_error);
    }
    else {
        // DB connection was successful

        /*
        1. User clicks on button to join group
        2. JS checks that numparticipants < maxparticipants; if so, proceed
        3. Pool is updated in DB to increment numparticipants
        4. New entry in Riders using USERID of logged in user and POOLID of clicked pool
        */

        // STEP 3
        $stmt = $conn->prepare("UPDATE Pools SET numparticipants=numparticipants + 1 WHERE poolid=?");
        $stmt->bind_param("s", $inData[poolid]);
        $stmt->execute();

        // STEP 4
        $stmt = $conn->prepare("INSERT INTO Riders (userid, poolid) VALUES (?, ?)");
        $stmt->bind_param("ss", $inData[userid], $inData[poolid]);
        $stmt->execute();

        $stmt->close();
    }

    // close database connection
    $conn->close();

    // get input from front end and decode json
    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    // return object to front end with json type
    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }
    
    // return an error to the front end with error message
    function returnWithError($err) {
        $retValue = '{"status": "error", "error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    // 
    function returnWithInfo($searchRes) {
        $retValue = '{"results": [' . $searchRes . '], "error":""}';
        sendResultInfoAsJson($retValue);
    }
?>