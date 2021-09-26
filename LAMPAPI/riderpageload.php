<?php
    // get json from frontend
    $inData = getRequestInfo();
    $searchRes = "";

    // connect to database
    $conn = new mysqli("localhost", "dbuser", "j20cdh32sajcpo", "uride");

    // check database connection status
    if ($conn->connect_error) {
        // return DB error
        returnWithError($conn->connect_error);
    }
    else {
        // DB connection was successful
        // SQL SET 1: Find Pools that User is in
        /// GETTING USERID FROM USERS TABLE
        $stmt = $conn->prepare("SELECT poolid FROM Riders WHERE userid=?");
        $stmt->bind_param("s", $inData["id"]);
        $result = $stmt->get_result();

        $searchCount = 0;

        while ($row = $result->fetch_assoc()) {
            /// GETTING ID FROM RIDERS TABLE
            $stmt2 = $conn->prepare("SELECT * FROM Pools WHERE id=?");
            $stmt2->bind_param("s", $row["poolid"]);
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();

            $searchCount++;
            
            $searchRes .= '{"id": "' . $row2['id'] . '", "numparticipants": "' . $row2['numparticipants'] . '", "maxparticipants": "' . $row2['maxparticipants'] . '", "days": "' . $row2['days'] . '", "time": "' . $row2['time'] . '", "origin": "' . $row2['origin'] . '", "destination": "' . $row2['destination'] . '"}';
        }
        
        if ($searchCount == 0) {
            returnWithError("No Carpools found.");
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