<?php
    
    $inData = getRequestInfo();
    
    $userName = $inData["userName"];
    $passWord = $inData["passWord"];
    
    $UID = 0;
    $firstName = "";
    $lastname = "";
    
    $conn = new mysqli("127.0.0.1", "root", "", "ManagerDB");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "SELECT UID, firstName, lastName FROM Users WHERE userName='" . $userName . "' AND passWord='" . $password . "'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0)
        {
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
        
        $conn->close();
    }
  
    function getRequestInfo()
    {
        return json_decode(file_get_contents("php://input"), true);
    }
    
    function sendResultInfoAsJson($obj)
    {
        header('Content: application/json');
        echo $obj;
    }
    
    function returnWithInfo($firstName, $lastName, $UID)
    {
        $retValue = '{ "UID" : "' . $UID . '" , "firstName" : "' . $firstName . '" , "lastName" : "' . $lastName . '"}';
        sendResultInfoAsJson( $retValue );
    }
    
    function returnWithError($err)
    {
        $retValue = '{ "UID" : 0 , "firstName" : "", "lastName" : "" , "error" : "' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
