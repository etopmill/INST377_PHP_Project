<?php

// Set the database access information as constants.
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', ''); // insert your own password
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'globalwarming');

// Make the connnection and select the database.
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

?>