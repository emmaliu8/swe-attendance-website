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

$memberName = trim($_GET["memberName"]);
$memberKerb = trim($_GET["kerb"]);
$memberPosition = trim($_GET["position"]);
$memberDepartment = $_GET["department"];

$sql = "INSERT INTO Members (Kerberos, Name, Position, Department, IsBoardMember) VALUES ('".$memberKerb."', '".$memberName."', '".$memberPosition."', '".$memberDepartment."', '1')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>