<?php
$servername = "indiv";
$username = "root";
$password = "";
$dbname = "indiv";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
