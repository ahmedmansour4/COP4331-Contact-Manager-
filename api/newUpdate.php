<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $CID = $inData["CID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phoneNumber = $inData["phoneNumber"];
    $email = $inData["email"];
    $notes = $inData["notes"];
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "UPDATE Contacts SET firstName = '$firstName', lastName = '$lastName', phoneNumber = '$phoneNumber', email = '$email', notes = '$notes' WHERE CID = '$CID' AND UID = '$UID'";
        
        if ($conn->query($sql) === TRUE)
        {
            returnWithInfo();
        }
        else
        {
            returnWithError("Could not update contact");
        }
        
        $conn->close();
    }
    
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }
    
    function returnWithInfo()
    {
        $retValue = '{"Mess":' . "Contact Updated" . ', "error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
