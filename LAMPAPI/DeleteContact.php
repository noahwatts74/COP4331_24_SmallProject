<?php

    $inData = getRequestInfo();
        
    $id = 0;
    $firstName = "";
    $lastName = "";
    $conn = new mysqli("localhost", "allanmc2_team24_1", "test1234!", "allanmc2_team24");
    if ($conn->connect_error) 
    {
        returnWithError( $conn->connect_error );
    } 
    else
    {   
        $sql = "SELECT FirstName, LastName, Phone FROM Contacts WHERE 
                ParentLogin='" . $inData["ParentLogin"] . "' AND
                FirstName='" . $inData["FirstName"] . "' AND
                LastName='" . $inData["LastName"] . "' AND
                Phone='" . $inData["Phone"] . "'";
        $result = mysqli_query($conn, $sql);
        if($result->num_rows == 0){
            returnWithError("No Contact Found");
        }
        else{
            $sql = "DELETE FROM Contacts WHERE 
            ParentLogin='" . $inData["ParentLogin"] . "' AND
            FirstName='" . $inData["FirstName"] . "' AND
            LastName='" . $inData["LastName"] . "' AND
            Phone='" . $inData["Phone"] . "'";
            $del = $conn->query($sql);

            returnWithInfo($inData["FirstName"],$inData["LastName"],$inData["Phone"]);
        }
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson( $obj )
    {
        header('Content-type: application/json');
        echo $obj;
        return $obj;
    }

    function returnWithError( $err )
    {
        $retValue = '{"ParentLogin":"0","firstName":"","lastName":"","Phone":"","Email":"","Date":"","error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithInfo($firstName, $lastName, $Phone)
    {
        $retValue = '
            "Phone":"' . $Phone . '", 
            "firstName":"' . $firstName . '",
            "lastName":"' . $lastName . '",
            "error":""}';
        sendResultInfoAsJson( $retValue );
    }
 ?>