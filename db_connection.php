<?php
define ('SITE_ROOT', realpath(dirname(__FILE__)));// site root
//database connection
$servername = "localhost";
$username = "root";
$password = "";
$db = "practice_test";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>