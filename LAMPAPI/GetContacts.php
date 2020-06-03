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
        $sql = "SELECT FirstName, LastName, Phone, Email, Date, ParentLogin FROM Contacts WHERE 
                ParentLogin='" . $inData["ParentLogin"] . "'";
        $result = $conn->query($sql);
        $rows = array();
        // while ( $row = $result->fetch_assoc())  {
        //     echo($row);
        //   }
        //  echo json_encode($rows);
        $row = result->fetch_row();
        $firstName = $row["FirstName"];
        echo($firstName);
        // while ($row = $result->fetch_row()) {
        //     // $firstName = $row["FirstName"];
        //     // $lastName = $row["LastName"];
        //     // $ParentLogin = $row["ParentLogin"];
        //     // $Email = $row["Email"];
        //     // $Date = $row["Date"];
        //     // $Phone = $row["Phone"];
        //     // echo($firstName);
        // //     $tempObj = new stdClass;
        // //     $tempObj->FirstName = $firstName;
        // //     $tempObj->LastName = $LastName;
        // //     $tempObj->ParentLogin = $ParentLogin;
        // //     $tempObj->Email = $Email;
        // //     $tempObj->Date = $Date;
        // //     $tempObj->Phone = $Phone;
        // //     echo "derf";
        // //     $rows[$index] = $tempObj;
        // //     // $tempArr = array('FirstName' => $firstName,
        // //     //                 'LastName' => $LastName,
        // //     //                 'ParentLogin' => $ParentLogin,
        // //     //                 'Email' => $Email,
        // //     //                 'Date' => $Date,
        // //     //                 'Phone' => $Phone)
        // //     $index++;
        // //     // returnWithInfo($firstName, $lastName, $Phone, $Date, $Email, $ParentLogin)
        // }
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

    function returnWithInfo($rows)
    {
    	sendResultInfoAsJson($rows);
    }

 ?>