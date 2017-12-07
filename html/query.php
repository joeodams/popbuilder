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
    $init = "CREATE TABLE temptable AS (SELECT Postcode, latitude, longitude, Total
    FROM geodem.populations
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 1:
    $init = "CREATE TABLE temptable AS (SELECT pcd_fix, latitude, longitude, Total
    FROM geodem.house_prices
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 2:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, Total
    FROM geodem.crimes
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 3:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, unemployed_adults AS Total
    FROM geodem.unemployment
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 4:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, christian AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 5:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, buddhist AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 6:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, hindu AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 7:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, jewish AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 8:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, muslim AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 9:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, sihk AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

    $sql = "SELECT * FROM temptable";

    $maxsql = "SELECT AVG(Total) AS Average, STDDEV(Total) AS Stddev,MAX(Total) AS Maximum
    FROM temptable";
    break;

  case 10:
    $init = "CREATE TABLE temptable AS (SELECT latitude, longitude, no_religion AS Total
    FROM geodem.religion
    WHERE latitude BETWEEN ($lat - 0.2) AND ($lat + 0.2) AND longitude BETWEEN ($long - 0.2) AND ($long + 0.2))";

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
