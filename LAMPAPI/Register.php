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

    $sql = "INSERT INTO Users(Login, Password) VALUES ('" . $inData["Login"] . "', '" . $inData["Password"] . "')";
    $conn->query($sql);
    sendResultInfoAsJson($inData["Login"],$inData["Password"]);

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
	$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
	sendResultInfoAsJson( $retValue );
}

function returnWithInfo( $firstName, $lastName, $id )
{
	$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
	sendResultInfoAsJson( $retValue );
}

?>