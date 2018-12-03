<?php

define('DICT_FILE_PATH', './dictionary.txt');

$dollarNull = $_SERVER['SCRIPT_URI'];

function getParam($name, $fallback = '') {
    return isset($_GET[$name]) && $_GET[$name] != ""
        ? $_GET[$name]
        : $fallback;
}

$generators = array(
    'alnum' => function($length) {
        static $chars = null;
        static $lastChar = null;

        if ($chars === null) {
            $chars = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
            $lastChar = count($chars) - 1;
        }

        $out = '';
        for($i = 0; $i < $length; $i++) {
            $out .= $chars[rand(0, $lastChar)];
        }

        return $out;
    },
    'passphrase' => function($length) {
        static $wordFile = null;
        static $maxRand = null;

        if ($wordFile === null) {
            $maxRand = filesize(DICT_FILE_PATH);
            $wordFile = fopen(DICT_FILE_PATH, 'r');
        }

        $words = '';

        for($i = 0; $i < $length; $i++) {
            fseek($wordFile, mt_rand(0, $maxRand));
            fgets($wordFile);

            $words .= trim(fgets($wordFile));

            rewind($wordFile);
        }

        return $words;
    }
);

$formatters = [
    'text' => function($passwords, $error = false, $responseCode = 200) {
        header("Content-Type: text/plain");
        http_response_code($responseCode);

        if($error !== false) {
            echo $error;
            return;
        }

        foreach ($passwords as $pwd) {
            echo $pwd . PHP_EOL;
        }
    },
    'json' => function($passwords, $error = false, $responseCode = 200) {
        header("Content-Type: application/json");
        http_response_code($responseCode);

        if($error !== false) {
            echo json_encode([ 'error' => $error ]);
            return;
        }

        echo json_encode([ 'data' => $passwords ]);
    }
];

$type = getParam('type', 'alnum');
$count = getParam('count', 1);
$length = getParam('length', 16);
$format = getParam('format', 'text');
$help = getParam('help', false);

if ($help !== false) {
    include 'help.html';
    exit(0);
}

$error = false;

if(!array_key_exists($type, $generators)) {
    $error = "Invalid type \"$type\"";
    $errorType = 400;
}
if($count < 1) {
    $error = "Count must be > 0";
    $errorType = 400;
}
if($count > 100) {
    $error = "Count must be ≤ 100";
    $errorType = 400;
}
if($length < 1) {
    $error = "Length must be > 0";
    $errorType = 400;
}
if($length > 128) {
    $error = "Length must be ≤ 128";
    $errorType = 400;
}
if(!array_key_exists($format, $formatters)) {
    $error = "Invalid format \"$format\"";
    $errorType = 400;
    $format = 'text';
}

if ($error !== false) {
    $formatters[$format](null, $error, $errorType);
    exit(0);
}

$passwords = [];
for ($i = 0; $i < $count; $i++) {
    $passwords[] = $generators[$type]($length);
};
$formatters[$format]($passwords);



