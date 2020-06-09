<?php

/* 
log user in database, plus game build, time, and ip address
*/
session_start();

// ---- Connect to DB ----
include 'database.php';
// -----------------------

$query = 'INSERT INTO ewing_temp.public."uid_table" (ip, uid, t_epoch, game_build) VALUES ($1, $2, $3, $4)';
$result = pg_prepare($connect,"qry1",$query);
$result = pg_execute($connect,"qry1",array($_SESSION["ip"],$_SESSION["uid"],$_SESSION["time"],intval($_SESSION["gameBuild"])));

?>