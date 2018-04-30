<?php

// Создание дампа базы

require_once '../inc/conf.php';
require_once '../classes/mysqliDB.php';


function send($status, $data='') {
	die(json_encode(['status'=> $status, 'data'=> $data]));
}


$dir = $cfg['rootDir'] . '/dumps/';
$file = $dir.'mysql_backup_'.date("Y-m-d", mktime()).'.sql.gz';


if (!file_exists($dir)) {
	if (!mkdir($dir, 0777, true))
		send('err', 'Не удалось создать директорию: '.$dir);

	if (!chmod($dir, 0777))
		send('err', 'Не удалось выставить права 777 на директорию: '.$dir);
}



exec("mysqldump -h {$cfg['dbhost']} -u {$cfg['dbuser']} --password={$cfg['dbpass']} {$cfg['dbname']} | gzip > {$file}", $o);

if (count($o) === 0)
	send('ok', 'Дамп создан успешно: '.$file);
else
	send('err', $o);