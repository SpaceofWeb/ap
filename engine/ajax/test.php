<?php

ini_set('display_errors', 1);


require_once '../inc/conf.php';
require_once '../inc/db.php';
require_once '../classes/db.php';
require_once '../classes/docx2text.php';


$file = '/home/space/dev/sites/ap/uploads/diplomas/2018/sid6.docx';

try {
	$d2t = new DocumentParser();
	$text = $d2t->parseFromFile($file);
} catch(Exeption $e) {
	die($e);
}


die($text);