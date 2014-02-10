<?php

header("Content-Type: text/plain");

$type = isset($_GET['type']) ? $_GET['type'] : "crypt";
$times = isset($_GET['times']) ? (int) $_GET['times'] : 1;

if($type == "crypt")
{
	$length = isset($_GET['length']) ? (int) $_GET['length'] : 32;
	
	$out = array($length);
	
	for(var $i = 0; $i < $length; $i++)
		array_push($out, "x");
	
	$passphrase = join($out);
	
}
elseif($type == "memo")
{
	$length = isset($_GET['length']) ? (int) $_GET['length'] : 4;
	$passphrase = "";
}
else
	die("Unknown type");

var_dump($length);
echo PHP_EOL;
var_dump($type);
echo PHP_EOL;
var_dump($passphrase);
echo PHP_EOL;
