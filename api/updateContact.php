<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $infoToBeUpdated = $inData["update"];
    $updatedInfo = $inData["updatedInfo"];
    
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
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
        $retValue = '{"Mess":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
