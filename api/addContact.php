<?php
    
    // Extract users information and get it as an array
    $inData = getRequestInfo();
    
    // Take contact information and store them in appropriate fields
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $phoneNumber = $inData["phoneNumber"];
    $notes = $inData["notes"];
    
    // Establish a secure connection to the database
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    // Verify connection to the database has been made
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        // Create an sql statement that will insert the contact, linking it to the user with the users ID
        $sql = "INSERT INTO Contacts (UID, firstName, lastName, email, phoneNumber, notes) VALUES ('$UID', '$firstName', '$lastName', '$email', '$phoneNumber', '$notes')";
        
        // Returning false here indicates an error when inserting the contact. If not just return the firstName and
        // lastName of the contact
        if ($result = $conn->query($sql) != true)
        {
            returnWithError($conn->error);
        }
        else
        {
            returnWithInfo($firstName, $lastName);
        }
        
        // Close the connection to the database
        $conn->close();
    }
    
    // This function will decode the json package
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    // Sends information queried from the database, to the javascript on the frontend as json
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo($obj);
    }
    
    // Creates a json package with information queried from the database
    function returnWithInfo($firstName, $lastName)
    {
        $retValue = '{"Mess":"' . "Success!" . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    // Creates a json package with an error message
    function returnWithError($err)
    {
        $retValue = '{"Mess":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
