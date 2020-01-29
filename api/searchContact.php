<?php
    
    $inData = getRequestInfo();
    
    $UID = $inData["UID"];
    $search = $inData["search"];
    
    $searchResults = "";
    
    $conn = new mysqli("sql9.freemysqlhosting.net", "sql9319845", "l64JHb7YZj", "sql9319845", "3306");
    
    if ($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "SELECT * FROM Contacts WHERE ((CONCAT_WS(' ', firstName, lastName, email, phoneNumber) LIKE '%$search%' OR concat(' ', firstName, lastName, email, phoneNumber) LIKE '%$search%')) AND UID = '$UID'";
        
        $result = $conn->query($sql);
        
        $searchResults = getResults($result);
        
        if ($searchResults == "")
        {
            returnWithError("No Records Found");
        }
        else
        {
            returnWithInfo($searchResults);
        }
        
        $conn->close();
    }
    
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    function getResults($result)
    {
        $searchResults = "";
        $searchCount = 0;
        
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                if ($searchCount > 0)
                {
                    $searchResults .= ",";
                }
                
                $searchCount++;
                $searchResults .= '"' . $row["firstName"] . ' ' . $row["lastName"] . ' ' . dashedNum($row["phoneNumber"]) . ' ' . $row["email"] . ' ' . $row["notes"] . '"';
                
            }
        }
        
        return $searchResults;
    }
    
    function dashedNum($phoneNumber)
    {
        $phoneNumber = substr($phoneNumber, 0, 3) .'-'.
        substr($phoneNumber, 3, 3) .'-'.
        substr($phoneNumber, 6);
        
        return $phoneNumber;
    }
    
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }
    
    function returnWithInfo($searchResults)
    {
        $retValue = '{"results":[' . $searchResults . '],"error":""}';
        sendResultInfoAsJson($retValue);
    }
    
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
