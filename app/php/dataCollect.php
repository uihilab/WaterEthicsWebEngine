<?php

// session_start();

// ---- Connect to DB ----
include 'database.php';
// -----------------------

$build = $_GET["build"]."-";
$gB_length =strlen($build);


$query = 'SELECT uid,scenario,response FROM public.response_table WHERE LEFT(response_table.uid,$2)=$1';

$result = pg_prepare($connect,"qry1",$query);
$result = pg_execute($connect,"qry1",array($build,$gB_length));

$arr = pg_fetch_all($result);

echo json_encode($arr);
?>