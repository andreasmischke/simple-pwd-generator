<?php

header("Content-Type: text/plain");

$type = isset($_GET['type']) && $_GET['type'] != "" ? $_GET['type'] : "crypt";
$times = isset($_GET['times']) && $_GET['times'] != "" ? (int) $_GET['times'] : 1;

if($type == "crypt")
{
	$length = isset($_GET['length']) && $_GET['length'] != "" ? (int) $_GET['length'] : 32;
	$chars = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
	$passphrases = array();
	
	for($k = 0; $k < $times; $k++)
	{
		$out = array();
		for($i = 0; $i < $length; $i++)
			array_push($out, $chars[rand(0,count($chars)-1)]);
		
		array_push($passphrases, join($out));
	}
}
#elseif($type == "memo")
#{
#	$length = isset($_GET['length']) && $_GET['length'] != "" ? (int) $_GET['length'] : 4;
#	$passphrase = "";
#}
else
	die("Unknown type");

foreach ($passphrases as $passphrase) {
	echo $passphrase;
	echo PHP_EOL;
}
