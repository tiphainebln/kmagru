<?php
include 'database.php';
// CREATE DATABASE
try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE `".$DB_NAME."`";
        $dbh->exec($sql);
        echo "Database created.\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING DB: \n".$e->getMessage();
        exit(-1);
    }
// CREATE TABLE USER
try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE `user` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `username` VARCHAR(50) NOT NULL,
          `email` VARCHAR(100) NOT NULL,
          `password` VARCHAR(100) NOT NULL,
          `hash` VARCHAR(32) NOT NULL,
          `active` TINYINT(1) NOT NULL DEFAULT 0
        )";
        $dbh->exec($sql);
        echo "Table user created.\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage();
    }
// CREATE TABLE GALLERY
try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE `gallery` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `userid` INT(11) NOT NULL,
          `img` VARCHAR(100) NOT NULL,
          FOREIGN KEY (userid) REFERENCES users(id)
        )";
        $dbh->exec($sql);
        echo "Table gallery created.\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage().;
    }
// CREATE TABLE LIKE
try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE `like` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `userid` INT(11) NOT NULL,
          `galleryid` INT(11) NOT NULL,
          `type` VARCHAR(1) NOT NULL,
          FOREIGN KEY (userid) REFERENCES users(id),
          FOREIGN KEY (galleryid) REFERENCES gallery(id)
        )";
        $dbh->exec($sql);
        echo "Table like created.\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage();
    }
// CREATE TABLE COMMENT
try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE `comment` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `userid` INT(11) NOT NULL,
          `galleryid` INT(11) NOT NULL,
          `comment` VARCHAR(255) NOT NULL,
          FOREIGN KEY (userid) REFERENCES users(id),
          FOREIGN KEY (galleryid) REFERENCES gallery(id)
        )";
        $dbh->exec($sql);
        echo "Table comment created.\n";
    } catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage();
    }
?>