<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $verify = "SELECT firstName, lastName FROM Contacts WHERE UID = '$UID' AND firstName = '$firstName' AND lastName = '$lastName'";
        $nonExistent = $conn->query($verify);
        
        if ($nonExistent->num_rows > 0)
        {
            $sql = "DELETE FROM Contacts WHERE UID = '$UID' AND firstName = '$firstName' AND lastName = '$lastName'";
            
            if ($conn->query($sql) === TRUE)
            {
                returnWithInfo();
            }
        }
        else
        {
            returnWithError("Contact not found");
        }
        
        $conn->close();
    }
    
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    function returnWithInfo()
    {
        $retValue = '{"Mess":' . "Contact Deleted" . ', "error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo($obj);
    }
    
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
