<?php
$db_str = "host={your-url-here} port=5432 dbname={db-name-here} user={username} password={your-password}";

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");

try {
    $connect=@pg_connect($db_str);
    $_SESSION["connection"] = True;
} Catch (Exception $e) {
    Echo $e->getMessage();
    $_SESSION["connection"] = False;
}
?>