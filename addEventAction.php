<?php
$servername = "sql.mit.edu";
$username = "emmaliu";
$password = "mitswe19";
$dbname = "emmaliu+swe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$eventName = trim($_GET["eventName"]);
$eventPassword = trim($_GET["eventPassword"]);
$eventType = $_GET["eventType"];

$sql = "INSERT INTO Events (Name, Password, EventType) VALUES ('".$eventName."', '".$eventPassword."', '".$eventType."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>