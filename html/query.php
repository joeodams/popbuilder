<?php
$postdata = file_get_contents("php://input");

$coord = json_decode($postdata,true);

$type = $coord["type"];

$lat = $coord["loc"][0];
$long = $coord["loc"][1];

$servername = "localhost";
$username = "root";
$password = " ";
$dbname = "geodem";

// Create connection
$conn = new mysqli($servername, $username);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$db = "USE geodem;";

$conn->query($db);

switch ($type) {
  case 0:
    $init = "CREATE TABLE temptable AS (SELECT pcd.Postcode, Latitude, Longitude, Total
    FROM geodem.uk_postcodes AS pcd
    INNER JOIN geodem.populations AS pop ON pop.pcd_fix = pcd.pcd_fix
    WHERE Latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND Longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 1:
    $init = "CREATE TABLE temptable AS (SELECT pcd.Postcode, Latitude, Longitude, (Total / 5000) AS Total
    FROM geodem.uk_postcodes AS pcd
    INNER JOIN geodem.house_prices AS ppd ON ppd.pcd_fix = pcd.pcd_fix
    WHERE Latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND Longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;
}

$drop = "DROP TABLE temptable";

$conn->query($drop);

if (!$result = $conn->query($init)) {
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

if (!$maxresult = $conn->query($maxsql)) {
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

$maxrow = array();

while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}

while($m = mysqli_fetch_assoc($maxresult)) {
    $maxrow[] = $m;
}

//echo $sql . "\n";
//echo $conn->affected_rows . "\n";


//echo $type;
//echo $long;

echo json_encode(array($maxrow,$rows));



//
//echo json_encode(array_values($ret));
 ?>
