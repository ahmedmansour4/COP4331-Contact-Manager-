<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phoneNumber = $inData["phoneNumber"];
    
    $CID = -1;
    
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "SELECT CID FROM Contacts WHERE UID='$UID' AND firstName='$firstName' AND lastName='$lastName' AND phoneNumber='$phoneNumber'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $CID = $row["CID"];
            
            returnWithInfo($CID);
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
    
    function returnWithInfo($CID)
    {
        $retValue = '{"Mess":"' . $CID . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function returnWithError($err)
    {
        $retValue = '{"Mess":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
