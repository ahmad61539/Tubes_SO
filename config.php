<?php

$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>