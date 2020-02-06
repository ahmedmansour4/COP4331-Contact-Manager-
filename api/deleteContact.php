<?php
    
    // Extract contacts information
    $inData = getRequestInfo();
    
    // Place contact info in appropriate variables from the inData array
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];

    // Establish a secure connection to the database
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    // Verify that a connection to the database has been made before proceeding
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        // Should first verify that a given contact to delete actually exists
        $verify = "SELECT firstName, lastName FROM Contacts WHERE UID = '$UID' AND firstName = '$firstName' AND lastName = '$lastName'";
        $nonExistent = $conn->query($verify);
        
        // The presence of rows indicates that there is a contact, go ahead and delete from the contacts list
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
        
        // Close the connection to the database
        $conn->close();
    }
    
    // This function will decode the json package
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    // Creates a json package with information queried from the database
    function returnWithInfo()
    {
        $retValue = '{"Mess":' . "Contact Deleted" . ', "error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    // Sends information queried from the database, to the javascript on the frontend as json
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo($obj);
    }
    
    // Creates a json package with an error message
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
