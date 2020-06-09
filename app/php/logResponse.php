<?php
	session_start();


	/*
	Submit response of a scenario to the database.
	*/


	// ---- Connect to DB ----
	include 'database.php';
	// -----------------------

	$scenario = $_GET['scenario'];
	$LR = $_GET['LR'];
	$click = $_GET['click'];


	$query = 'INSERT INTO ewing_temp.public."response_table" (uid, scenario, response, click) VALUES ($1, $2, $3, $4)';

	$result = pg_prepare($connect,"qry1",$query);
	$result = pg_execute($connect,"qry1",array($_SESSION["uid"],$scenario,$LR,$click));
?>