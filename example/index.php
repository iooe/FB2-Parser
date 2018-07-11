<?php

include '../vendor/autoload.php';

use Tizis\FB2\FB2Controller;

$imagesPath = $_SERVER['DOCUMENT_ROOT'].'/img/1';
$file = file_get_contents('file - Copy.fb2');
$item = new FB2Controller($file);
$item->withNotes();
$item->withImages(['directory' => $imagesPath, 'imagesWebPath' => '/img/1']);
$item->startParse();
echo "memory_usage: ".(memory_get_usage(true)/1024/1024)." MiB\n\n";
dump($item->getBook());
dump($item->getBook()->getAuthors()[0]->getFirstName());
dump($item->getBook()->getChapters());

