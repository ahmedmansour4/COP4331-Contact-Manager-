<?php
    
    $inData = getRequestInfo();
    
    $userName = $inData["userName"];
    $password = md5($inData["password"]);
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ( $conn->connect_error )
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        $checkStatus = "SELECT userName FROM Users WHERE userName='".$userName."'";
        $alreadyTaken = $conn->query($checkStatus);
        
        if ($alreadyTaken->num_rows > 0)
        {
            returnWithError( "That userName is not available" );
        }
        else
        {
            $sql = "INSERT INTO Users (userName, password, firstName, lastName, email) VALUES ('$userName', '$password', '$firstName', '$lastName', '$email')";
            
            if ( $result = $conn->query($sql) != TRUE)
            {
                returnWithError ($conn->error);
            }
            
            returnWithInfo($firstName, $lastName);
        }
        $conn->close();
    }
    
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    function sendResultInfoAsJson( $obj )
    {
        header('Content-type: application/json');
        echo $obj;
    }
    
    function returnWithInfo($firstName, $lastName)
    {
        $retValue = '{"Mess":' . "Success!" . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function returnWithError( $err )
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
