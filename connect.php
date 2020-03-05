<?php
$host = "59.153.23.27,1599";
$dbname = "AS-PANDES";
$user = "aspano_ro";
$pass = "P@n0ram@1599";
$dsn = "odbc:tourplan";
$dbh = new PDO("$dsn","$user", "$pass");
$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

set_time_limit(0);
?>