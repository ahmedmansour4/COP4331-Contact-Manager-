<?php
    
    // Extract users information and get it as an array
    $inData = getRequestInfo();
    
    $userName = $inData["userName"];
    $passWord = $inData["passWord"];
    
    // We first want to initialize UID as zero, if any user information is incorrect a zero will be sent back signaling
    // user error
    $UID = 0;
    $firstName = "";
    $lastName = "";

    // Connect to the database    
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    // Verify the connection
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        // This creates an sql statment once a connection has been made to verify the users credentials
        $sql = "SELECT UID, firstName, lastName FROM Users WHERE userName='" . $userName . "' AND passWord='" . $passWord . "'";
        
        // Query the database with the sql statement
        $result = $conn->query($sql);
        
        // The existence of rows indicates the a successful query and the user is in the database
        if ($result->num_rows > 0)
        {
            // Fetch the associated row and return it as an array to extract firstName, lastName and UID
            $row = $result->fetch_assoc();
            $firstName = $row["firstName"];
            $lastName = $row["lastName"];
            $UID = $row["UID"];
            
            returnWithInfo($firstName, $lastName, $UID);
        }
        else
        {
            returnWithError("No Records Found");
        }
        
        // Lastly close the connection to the database
        $conn->close();
    }
  
    // This function will decode the json package
    function getRequestInfo()
    {
        return json_decode(file_get_contents("php://input"), true);
    }
    
    // Sends information queried from the database, to the javascript on the frontend as json
    function sendResultInfoAsJson($obj)
    {
        header('Content: application/json');
        echo $obj;
    }
    
    // Creates a json package with information queried from the database
    function returnWithInfo($firstName, $lastName, $UID)
    {
        $retValue = '{ "UID" : "' . $UID . '" , "firstName" : "' . $firstName . '" , "lastName" : "' . $lastName . '"}';
        sendResultInfoAsJson( $retValue );
    }
    
    // Creates a json package with an error message
    function returnWithError($err)
    {
        $retValue = '{ "UID" : 0 , "firstName" : "", "lastName" : "" , "error" : "' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
