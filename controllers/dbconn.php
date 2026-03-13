<?php

$server = "localhost";
$user = "root";
$password = "";
$database = "kaivera";
$port = 3306;
$dsn = "mysql:host=$server;port=$port; dbname=$database";


/*
try {
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
*/

