<?php

/**
 * Configuration for database connection
 *
 */

$host       = "localhost";
$username   = "php-pdo";
$password   = "php-pdo";
$dbname     = "phppdo";
$dbtable    = "kamera";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );

///   imgproxy
$key = '943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881';
$salt = '520f986b998545b4785e0defbc4f3c1203f22de2374a3d53cb7a7fe9fea309c5';
