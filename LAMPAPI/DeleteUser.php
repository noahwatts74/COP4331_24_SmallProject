<?php

	$inData = getRequestInfo();
	
	$id = 0;
	$firstName = "";
	$lastName = "";
	//$Username = $inData["Username"];
	//$Password = $inData["Password"];
	$conn = new mysqli("localhost", "allanmc2_team24_1", "test1234!", "allanmc2_team24");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql2 = "SELECT Login FROM Users WHERE Login='" . $inData["Login"] . "'";
    	$result2 = $conn->query($sql2);
		
		if($result2->num_rows > 0) {
			$sql = "DELETE FROM Users WHERE Login='" . $inData["Login"] . "'";
			$conn->query($sql);
			returnWithInfo($inData["Login"]);
		} else {
			returnWithError($inData["Login"]);
		}		
		$conn->close();
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError($Id)
	{
		$retValue = '{"id":' . $id . ',"deleted":"False","error":"User does not exist"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo($id )
	{
		$retValue = '{"id":' . $id . ',"deleted":"True","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>