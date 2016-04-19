<?php

require 'vendor/autoload.php';


$fileMap = [
    // pkar
    '1WGKorzvde8Bxg-Nm9z0NbBTMfp7QKx2rh7FDbhNajjA' => 'https://docs.google.com/spreadsheets/d/1WGKorzvde8Bxg-Nm9z0NbBTMfp7QKx2rh7FDbhNajjA/gviz/tq?tqx=out:csv',
    '1ivCFmtnag_nJ4Vur5526xukFT656oy31FFSOD2k6RTY' => 'https://docs.google.com/spreadsheets/d/1ivCFmtnag_nJ4Vur5526xukFT656oy31FFSOD2k6RTY/gviz/tq?tqx=out:csv',
];

$requestedFile = isset($_GET['file']) ? $_GET['file'] : '';

if(!isset($fileMap[$requestedFile])) {
    header("Status: 404 Not Found");
    die("404 Not Found");
}

$fileName = __DIR__ . '/cache/' . $requestedFile . '.cache';
$cacheLength = 60 * 60;

if(!file_exists($fileName) || time() - filectime($fileName) > $cacheLength) {
    $client = new GuzzleHttp\Client([
        'verify' => __DIR__ . '/cacert.pem'
    ]);
    $response = $client->request('GET', $fileMap[$requestedFile]);
    file_put_contents($fileName, $response->getBody());
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $requestedFile . '.csv"');
header("Access-Control-Allow-Origin: *");
readfile($fileName);
die();
