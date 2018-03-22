<?php
include 'database.php';
// CREATE DATABASE

try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE IF NOT EXISTS camagru";
        $dbh->exec($sql);
        $sql = "USE camagru;
        CREATE TABLE `users` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `username` VARCHAR(50) NOT NULL,
          `email` VARCHAR(100) NOT NULL,
          `password` VARCHAR(100) NOT NULL,
          `hash` VARCHAR(60) NOT NULL,
          `active` VARCHAR(255) NOT NULL DEFAULT 0,
          `resetToken` varchar(255) DEFAULT NULL,
          `resetComplete` varchar(3) DEFAULT 'No');
          CREATE TABLE `gallery` (
          `galleryid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `userid` INT(11) NOT NULL,
          `img_name` VARCHAR(100) NOT NULL,
          `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (userid) REFERENCES users(id));
          CREATE TABLE `likes` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `userid` INT(11) NOT NULL,
          `galleryid` INT(11) NOT NULL,
          `username` VARCHAR(50) NOT NULL, 
          FOREIGN KEY (userid) REFERENCES users(id));
          CREATE TABLE `comment` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `userid` INT(11) NOT NULL,
          `galleryid` INT(11) NOT NULL,
          `comment` VARCHAR(255) NOT NULL,
          `username` VARCHAR(50) NOT NULL,
          FOREIGN KEY (userid) REFERENCES users(id));";
          $dbh->exec($sql);
    } catch (PDOException $e) {
        echo "ERROR CREATING DB: \n".$e->getMessage();
        exit(-1);
    }
?>
