<?php
    
    $inData = getRequestInfo();
    
    $userName = $inData["userName"];
    $password = $inData["password"];
    
    $id = 0;
    $firstName = "";
    $lastname = "";
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "SELECT id, firstName, lastName FROM Users WHERE userName = '$userName' AND password = '$password'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $firstName = $row["firstName"];
            $lastName = $row["lastName"];
            $id = $row["id"];
            
            returnWithInfo($firstName, $lastName, $id);
        }
        else
        {
            returnWithInfo("No Records Found");
        }
        
        $conn->close();
    }
  
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    function sendResultInfoAsJson($obj)
    {
        header('Content: application/json');
        echo $obj;
    }
    
    function returnWithInfo($firstName, $lastName, $id)
    {
        $retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson( $retValue );
    }
    
    function returnWithError($err)
    {
        $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
