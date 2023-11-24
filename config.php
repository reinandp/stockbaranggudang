<?php
$host = 'localhost';
$username = 'u346095446_reinand';
$password = 'XiaoWang22';
$database = 'u346095446_stockbarang';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}