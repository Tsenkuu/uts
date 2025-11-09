<?php
/*
File: db_connect.php
Deskripsi: File koneksi terpusat ke database MySQL menggunakan PDO.
*/



$db_host = 'localhost';    
$db_name = 'proto'; 
$db_user = 'root';         
$db_pass = '';             


$charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
   
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
   
   
   
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
