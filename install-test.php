$link = mysql_connect($host, $username, $password);
if (!$link) {
  die('Could not connect: ' . mysql_error());
}

// Make my_db the current database
$db_selected = mysql_select_db('my_db', $link);

if (!$db_selected) {
// If we couldn't, then it either doesn't exist, or we can't see it.
$sql = 'CREATE DATABASE my_db';

if (mysql_query($sql, $link)) {
    echo "Database my_db created successfully\n";
} else {
    echo 'Error creating database: ' . mysql_error() . "\n";
}
}

mysql_close($link);
