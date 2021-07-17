<?php

/**
 * Configuration for database connection
 *
 */

$host       = "localhost";
$username   = "php-pdo";
$password   = "php-pdo";
$dbname     = "phppdo";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );
