<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $phoneNumber = $inData["phoneNumber"];
    $notes = $inData["notes"];
    
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "INSERT INTO Contacts (UID, firstName, lastName, email, phoneNumber, notes) VALUES ('$UID', '$firstName', '$lastName', '$email', '$phoneNumber', '$notes')";
        
        if ($result = $conn->query($sql) != true)
        {
            returnWithError($conn->error);
        }
        else
        {
            returnWithInfo($firstName, $lastName);
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
        echo($obj);
    }
    
    function returnWithInfo($firstName, $lastName)
    {
        $retValue = '{"Mess":"' . "Success!" . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
