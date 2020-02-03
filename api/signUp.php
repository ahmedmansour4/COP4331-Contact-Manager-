<?php
    
    // Extract users information from the json
    $inData = getRequestInfo();
    
    $userName = $inData["userName"];
    $passWord = md5($inData["passWord"]);
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    
    // Connect to the database
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    if ( $conn->connect_error )
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        // Before inserting the user, check to make sure that username is not already taken
        $checkStatus = "SELECT userName FROM Users WHERE userName='".$userName."'";
        $alreadyTaken = $conn->query($checkStatus);
        
        // If the query returned any rows, that indicates there is alredy a user with that username
        if ($alreadyTaken->num_rows > 0)
        {
            returnWithError( "That userName is not available" );
        }
        else
        {
            // If we get here the username is available. This creates an sql statement to insert the user
            $sql = "INSERT INTO Users (userName, passWord, firstName, lastName, email) VALUES ('$userName', '$passWord', '$firstName', '$lastName', '$email')";
            
            // Insert the user, if its not possible, return an error
            if ( $result = $conn->query($sql) != TRUE)
            {
                returnWithError ($conn->error);
            }
            
            returnWithInfo($firstName, $lastName);
        }
        $conn->close();
    }
    
    // This function will decode the json package
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    // Sends information queried from the database, to the javascript on the frontend as json
    function sendResultInfoAsJson( $obj )
    {
        header('Content-type: application/json');
        echo $obj;
    }
    
    // Creates a json package with information provided by the user
    function returnWithInfo($firstName, $lastName)
    {
        $retValue = '{"Mess":"' . "Success!" . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    // Creates a json package with an error message
    function returnWithError( $err )
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
