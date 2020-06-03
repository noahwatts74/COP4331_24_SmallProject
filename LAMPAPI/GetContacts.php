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
        $sql = "SELECT ParentLogin, FirstName, LastName, Phone, Email, Date FROM Contacts WHERE 
                ParentLogin='" . $inData["ParentLogin"] . "'";
        $result = mysqli_query($conn, $sql);
        if($result->num_rows == 0){
            returnWithError("No Contacts Found");
        }
        else{
            while($row = mysqli_fetch_assoc($result)){
                $row->error = "";
                $test[] = $row;
            } 
            echo json_encode($test);
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

    function returnWithInfo($rows)
    {
    	sendResultInfoAsJson($rows);
    }

 ?>