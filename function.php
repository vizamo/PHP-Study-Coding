<?php
$conn = getConnection();
function getConnection() {
    $host = 'db.mkalmo.xyz';
    $user = 'vitali206810';
    $pass = "552f";
    $database = 'vitali206810';


    $address = sprintf('mysql:host=%s;dbname=%s', $host, $database);

    try {
        return new PDO($address, $user, $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        throw new \http\Exception\RuntimeException("cant connect");
    }
}

