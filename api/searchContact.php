<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phoneNumber = $inData["phoneNumber"];
    
    $searchResults = "";
    $searchCount = 0;
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "SELECT UID, firstName, lastName, phoneNumber FROM Contacts WHERE firstName LIKE '%" . $firstName . "%' OR lastName LIKE '%" . $lastName . "%' OR phoneNumber LIKE '%" . $phoneNumber . "%' AND UID = '$UID'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                if ($searchCount > 0)
                {
                    $searchResults .= ",";
                }
                
                $searchCount++;
                $searchResults .= '"' . $row["firstName"] . ' ' . $row["lastName"] . ' ' . $row["phoneNumber"] . '"';
            }
        }
        else
        {
            returnWithError("No Records Found");
        }
        
        $conn->close();
    }
    
    returnWithInfo($searchResults);
    
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    function sendResultInfoAsJson( $obj )
    {
        header('Content-type: application/json');
        echo $obj;
    }
    
    function returnWithInfo( $searchResults )
    {
        $retValue = '{"results":[' . $searchResults . '],"error":""}';
        sendResultInfoAsJson( $retValue );
    }
    
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
?>
