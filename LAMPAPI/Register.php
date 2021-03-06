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
    $sql = "SELECT Login FROM Users WHERE Login='" . $inData["Login"] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
	{
		// Return error because this input from the user is already associated
		// with another account
		$error = "This account already Exists";
		returnWithError($error);
	}
    else {
        $new = "INSERT INTO Users(FirstName, LastName, Login, Password) VALUES ('" . $inData["FirstName"] . "', '" . $inData["LastName"] . "', '". $inData["Login"] . "', '" . $inData["Password"] . "')";
		$conn->query($new);
        $newAcct = "SELECT Login, FirstName, LastName FROM Users WHERE Login='" . $inData["Login"] . "'";
        $result = $conn->query($newAcct);
        
        $row = $result->fetch_assoc();
        $firstName = $row["FirstName"];
        $lastName = $row["LastName"];
        $id = $row["Login"];
        returnWithInfo($firstName, $lastName, $id);
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
	$retValue = '{"Login":0,"FirstName":"","LastName":"","error":"' . $err . '"}';
	sendResultInfoAsJson( $retValue );
}

function returnWithInfo( $firstName, $lastName, $id )
{
	$retValue = '{"Login":"' . $id . '","FirstName":"' . $firstName . '","LastName":"' . $lastName . '","error":""}';
	sendResultInfoAsJson( $retValue );
}

?>