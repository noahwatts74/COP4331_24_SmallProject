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
    $sql = "SELECT FirstName, LastName, ParentLogin FROM Contacts WHERE 
            ParentLogin='" . $inData["ParentLogin"] . "' AND
            FirstName='" . $inData["FirstName"] . "' AND
            LastName='" . $inData["LastName"] . "' AND
            Phone='" . $inData["Phone"] . "'";
    $result = $conn->query($sql);
    $sql2 = "SELECT Login FROM Users WHERE Login='" . $inData["ParentLogin"] . "'";
    $result2 = $conn->query($sql2);
    if ($result->num_rows > 0)
	{
		// Return error because this input from the user is already associated
		// with another account
		$error = "This Contact already exists";
		returnWithError($error);
    }
    elseif($result2->num_rows == 0){
        $error = "The associated user does not exist";
		returnWithError($error);
    }
    else {
        $new = "INSERT INTO Contacts(FirstName, LastName, Phone, Email, Date, ParentLogin) VALUES (
        '" . $inData["FirstName"] . "',
        '" . $inData["LastName"] . "', 
        '" . $inData["Phone"] . "',
        '" . $inData["Email"] . "',
        '" . date("y/m/d h:i:s") . "',
        '" . $inData["ParentLogin"] . "')";
        $conn->query($new);
        $newContact = "SELECT FirstName, LastName, ParentLogin, Phone, Date FROM Contacts WHERE 
        ParentLogin='" . $inData["ParentLogin"] . "' AND
        FirstName='" . $inData["FirstName"] . "' AND
        LastName='" . $inData["LastName"] . "' AND
        Phone='" . $inData["Phone"] . "'";
        $result = $conn->query($newContact);
        
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