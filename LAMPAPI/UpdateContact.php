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
    $sql = "SELECT FirstName, LastName, ParentLogin, Phone, Email FROM Contacts WHERE 
            ParentLogin='" . $inData["ParentLogin"] . "' AND
            FirstName='" . $inData["OriginalFirstName"] . "' AND
            LastName='" . $inData["OriginalLastName"] . "' AND
            Phone='" . $inData["OriginalPhone"] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0)
	{
		// Return error because this input from the user is already associated
		// with another account
		$error = "Could not find specified contact";
		returnWithError($error);
    }
    else {
        $new = "UPDATE Contacts SET 
        FirstName='" . $inData["NewFirstName"] . "',
        LastName='" . $inData["NewLastName"] . "', 
        Phone='" . $inData["NewPhone"] . "',
        Email='" . $inData["NewEmail"] . "'
        WHERE
        FirstName='" . $inData["OriginalFirstName"] . "' AND
        LastName='" . $inData["OriginalLastName"] . "' AND
        Phone='" . $inData["OriginalPhone"] . "'";
        $conn->query($new);

        $UpdatedContact = "SELECT FirstName, LastName, ParentLogin, Phone, Email FROM Contacts WHERE 
        ParentLogin='" . $inData["ParentLogin"] . "' AND
        FirstName='" . $inData["NewFirstName"] . "' AND
        LastName='" . $inData["NewLastName"] . "' AND
        Phone='" . $inData["NewPhone"] . "'";
        $result = $conn->query($UpdatedContact);
        
        $row = $result->fetch_assoc();
        $firstName = $row["FirstName"];
        $lastName = $row["LastName"];
        $ParentLogin = $row["ParentLogin"];
        $Phone = $row["Phone"];
        returnWithInfo($firstName, $lastName, $Phone, $ParentLogin);
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
	$retValue = '{"ParentLogin":0,"Phone":"",FirstName":"","LastName":"","error":"'. $err . '"}';
	sendResultInfoAsJson( $retValue );
}

function returnWithInfo($firstName, $lastName, $Phone, $ParentLogin)
{
    $retValue = '{"ParentLogin":"' . $ParentLogin . '", 
        "Phone":"' . $Phone . '", 
        "firstName":"' . $firstName . '",
        "lastName":"' . $lastName . '",
        "error":""}';
	sendResultInfoAsJson( $retValue );
}

?>