<?php
// Check if the constants are already defined before defining them
if (!defined('DB_SERVER')) {
   define('DB_SERVER', 'localhost:3306');
}

if (!defined('DB_USERNAME')) {
   define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
   define('DB_PASSWORD', '');
}

if (!defined('DB_DATABASE')) {
   define('DB_DATABASE', 'webaes');
}

// Connect to the database
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if (!$db) {
   die("Connection failed: " . mysqli_connect_error());
}
