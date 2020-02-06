<?php
    
    $inData = getRequestInfo();
    
    $userName = $inData["userName"];
    $passWord = md5($inData["passWord"]);
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
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
            $sql = "INSERT INTO Users (email, userName, passWord, firstName, lastName) VALUES ('$email', '$userName', '$passWord', '$firstName', '$lastName')";
            
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
        $retValue = '{"Mess":"' . "Success!" . '" ,"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function returnWithError( $err )
    {
        $retValue = '{"Mess":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
