<?php

session_start();

// ---- Connect to DB ----
include 'database.php';
// -----------------------

$scenarios = json_decode('['.$_GET["scenarios"].']',true);

$gameBuild = $_SESSION["gameBuild"].'-';
$gB_length =strlen($gameBuild);

// LEFT(...) responsively filters by gamebuild which is variable length
$query = 'SELECT scenario,response FROM public.response_table WHERE LEFT(response_table.uid,$2)=$1 AND scenario in (';
for ($i = 0; $i < count($scenarios); $i++){
	$i_str = strval($i+3);

	$add = '$'.$i_str.',';
	$query .= $add;
}
$query = substr($query, 0,-1).')';

$result = $result = pg_prepare($connect,"qry2",$query);
$result = pg_execute($connect,"qry2",array_merge(array($gameBuild,$gB_length),$scenarios));


$arr = pg_fetch_all($result);

// Make results array
$results = array();
for ($i = 0; $i < count($scenarios); $i++){
	$results[$scenarios[$i]] = array("L"=>0,"R"=>0);
}

for ($i = 0; $i < count($arr); $i++){
	$results[$arr[$i]["scenario"]][$arr[$i]["response"]]++;
}

echo json_encode($results);
?>