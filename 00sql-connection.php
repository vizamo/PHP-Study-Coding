<?php
$conn = getConnection();

function getConnection() {
    $host = 'host';
    $user = 'user';
    $pass = "pass";
    $database = 'db_name';


    $address = sprintf('mysql:host=%s;dbname=%s', $host, $database);

    try {
       return new PDO($address, $user, $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        throw new \http\Exception\RuntimeException("cant connect");
    }
}
