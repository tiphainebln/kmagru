<?php
$DB_NAME = "camagru";
$DB_DSN = "mysql:host=127.0.0.1:3307;dbname=".$DB_NAME;
$DB_USER = "root";
$DB_PASSWORD = "root";
$conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD) or die($e->getMessage());
?>
