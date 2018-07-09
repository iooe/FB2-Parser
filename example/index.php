<?php

include '../vendor/autoload.php';

use FB2\FB2Controller;

$imagesPath = $_SERVER['DOCUMENT_ROOT'].'/img/1';
$file = file_get_contents('FILENAME.fb2');
$item = new FB2Controller($file);
$item->withNotes();
$item->withImages(['directory' => $imagesPath, 'imagesWebPath' => '/img/1']);
$item->startParse();
echo "real: ".(memory_get_peak_usage(true)/1024/1024)." MiB\n\n";
dump($item->getBook());
