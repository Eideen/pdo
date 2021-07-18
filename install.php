<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "config.php";

function tableExists($pdo, $table) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }
    echo "Table $table Exists\n";
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}

$dsn = "mysql:host=$host;charset=utf8"  ;
//echo "$dsn";
$pdo = new PDO($dsn, $username, $password, $options);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Check connection
    if ($pdo) {
    echo "Connected successfully to '$host'\n" ;
    }

    // Connect to MySQL
    $dbname = "`".str_replace("`","``",$dbname)."`";
    //echo "$dbname \n";
    $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->query("use $dbname");

    $dbtable = "`".str_replace("`","``",$dbtable)."`";
    if (!tableExists($pdo, $dbtable)) {
      $sql = file_get_contents("data/init.sql");
      $pdo->query($sql);
      echo "Table $dbtable in $dbname  created successfully.\n";
    }
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>
