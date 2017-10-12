<?php
$postdata = file_get_contents("php://input");

$coord = json_decode($postdata);

$lat = $coord[0];
$long = $coord[1];

$servername = "localhost";
$username = "user1";
//$password = "3in9h6u3kj5zvkTL";
$dbname = "CSV_DB";

// Create connection
$conn = new mysqli($servername, $username);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT pcd.Postcode, Latitude, Longitude, Total
FROM CSV_DB.uk_postcodes AS pcd
INNER JOIN CSV_DB.populations AS pop ON pop.Postcode = pcd.Postcode
WHERE Latitude BETWEEN ($lat - 0.0414) AND ($lat + 0.0414) AND Longitude BETWEEN ($long - 0.12885) AND ($long + 0.12885)";


if (!$result = $conn->query($sql)) {
    // Oh no! The query failed.

    // Again, do not do this on a public site, but we'll show you how
    // to get the error information
    echo $conn->host_info . "\n";
    echo $username . "\n";
    echo "Error: Our query failed to execute and here is why: \n";
    echo "Query: " . $sql . "\n";
    echo "Errno: " . $conn->errno . "\n";
    echo "Error: " . $conn->error . "\n";
    exit;
}

$rows = array();

while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}

//echo $sql . "\n";
//echo $conn->affected_rows . "\n";


echo json_encode($rows);


//
//echo json_encode(array_values($ret));
 ?>
