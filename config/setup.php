<?php
	include('database.php');
	try {
    	$conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	echo "Database created successfully<br>";
    }
	catch(PDOException $e){
    	echo 'Connection failed: ' . $e->getMessage();
   	 }
	$conn = null;
?>