<?php
    // get json from frontend
    $inData = getRequestInfo();

    // connect to database
    $conn = new mysqli("localhost", "dbuser", "j20cdh32sajcpo", "uride");

    // check database connection status
    if ($conn->connect_error) {
        // return DB error
        returnWithError($conn->connect_error);
    }
    else {
        // DB connection was successful

        // Select Carpools that have the same desired destination and start time
        $stmt = $conn->prepare("SELECT * FROM Pools WHERE destination=? AND time=? AND days=? AND numparticipants < maxparticipants");
        $stmt->bind_param("ss", $inData["destination"], $inData["startTime"], $inData["recurringDays"]);
        $stmt->execute();

        $result = $stmt->get_result();
        $foundCarpools = 0;

        while ($row2 = $result->fetch_assoc()) { 
            $foundCarpools = 1;

            $searchRes .= '{"id": "' . $row2['id'] . '", "numparticipants": "' . $row2['numparticipants'] . '", "maxparticipants": "' . $row2['maxparticipants'] . '", "days": "' . $row2['days'] . '", "time": "' . $row2['time'] . '", "origin": "' . $row2['origin'] . '", "destination": "' . $row2['destination'] . '"}';
        }

        if ($foundCarpools == 0) {
            returnWithError("No carpools found.");
        }
        else {
            returnWithInfo($searchRes);
        }

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