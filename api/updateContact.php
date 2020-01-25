<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $infoToBeUpdated = $inData["update"];
    $updatedInfo = $inData["updatedInfo"];
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "UPDATE Contacts SET  " . $infoToBeUpdated . " = '$updatedInfo' WHERE UID = '$UID' AND firstName = '$firstName' AND lastName = '$lastName'";
        
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
