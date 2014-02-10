<?php

$type = isset($_GET['type']) ? $_GET['type'] : "crypt";
if($type == "crypt")
{
	$length = isset($_GET['length']) ? (int) $_GET['length'] : 32;
}
elseif($type == "memo")
{
	$length = isset($_GET['length']) ? (int) $_GET['length'] : 4;
}
else
	die("Unknown type");



header("Content-Type: text/plain");

echo $length . PHP_EOL;
echo $type . PHP_EOL;