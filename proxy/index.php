<?php

require 'vendor/autoload.php';

$fileMap = array(
    // pkar
    '1WGKorzvde8Bxg-Nm9z0NbBTMfp7QKx2rh7FDbhNajjA' => 'https://docs.google.com/spreadsheets/d/1WGKorzvde8Bxg-Nm9z0NbBTMfp7QKx2rh7FDbhNajjA/gviz/tq?tqx=out:csv',
    '1ivCFmtnag_nJ4Vur5526xukFT656oy31FFSOD2k6RTY' => 'https://docs.google.com/spreadsheets/d/1ivCFmtnag_nJ4Vur5526xukFT656oy31FFSOD2k6RTY/gviz/tq?tqx=out:csv',
);

$requestedFile = isset($_GET['file']) ? $_GET['file'] : '';

if(!isset($fileMap[$requestedFile])) {
    header("Status: 404 Not Found");
    die("404 Not Found");
}

$fileName = __DIR__ . '/cache/' . $requestedFile . '.cache';
$cacheLength = 60 * 60; // 1h

if(!file_exists($fileName) || time() - filectime($fileName) > $cacheLength) {

    $cache = fopen($fileName, 'w');
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $fileMap[$requestedFile]);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FILE, $cache);
    curl_setopt($curl, CURLOPT_CAINFO, __DIR__.'/cacert.pem');

    curl_exec($curl);

    curl_close($curl);
    fclose($cache);
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $requestedFile . '.csv"');
header("Access-Control-Allow-Origin: *");
readfile($fileName);
die();
