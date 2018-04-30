<?php

// Добавление в базу

// ini_set('display_errors', 1);

require_once '../inc/conf.php';
// require_once '../inc/db.php';
require_once '../classes/mysqliDB.php';
require_once '../classes/docx2text.php';


function send($status, $data='') {
	die(json_encode(['status'=> $status, 'data'=> $data]));
}


$db = new MysqliDb($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpass'], $cfg['dbname']);
$db->setPrefix('ap_');


//////////////////////
// Получение данных //
//////////////////////
if (!isset($_POST['migration']) || $_POST['migration'] == '') {
	send('err', 'migration not valid');
}
$migration = $_POST['migration'];


$data = [];
foreach ($_POST as $key => $value) {
	if ($key == 'migration' || $key == 'id' || $key == 'file') continue;
	$data[$key] = $value;
}
// ==============================================================


////////////////////////////
// Если мы обновляем файл //
////////////////////////////
if (isset($_FILES['file']) && $migration == 'diplomas') {

	if ($_FILES['file']['error']) {
		send('err', 'File upload error: '.$_FILES['file']['error']);
	}

	// Проверяем есть ли дипломная у данного студента
	$db->where('student_id', $data['student_id']);
	$res = $db->get('diplomas', null, ['id']);

	if ($db->getLastError() !== 0) {
		send('err', 'DB error: '.$db->getLastError());
	}

	if ($db->count != 0) {
		send('err', 'diploma already exists');
	}

	// Сохранение дипломной
	$file = 'diplomas/';

	if (!file_exists($cfg['uploadDir'].$file)) {
		mkdir($cfg['uploadDir'].$file, 0777, true);
		chmod($cfg['uploadDir'].$file, 0777);
	}

	$file .= $data['year'].'/';

	if (!file_exists($cfg['uploadDir'].$file)) {
		mkdir($cfg['uploadDir'].$file, 0777, true);
		chmod($cfg['uploadDir'].$file, 0777);
	}


	if (!is_writable($cfg['uploadDir'].$file)) {
		send('err', 'Необходимо выставить права 777 на деректорию "'.$cfg['uploadDir'].'"');
	}

	$addDate = mktime();
	$file .= 'sid'.$data['student_id'].'.docx';

	if (!move_uploaded_file($_FILES['file']['tmp_name'], $cfg['uploadDir'].$file)) {
		send('err', 'Не удалось сохранить файл');
	}

	// Парсинг файла
	try {
		$d2t = new DocumentParser();
		$text = $d2t->parseFromFile($cfg['uploadDir'].$file, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
	} catch(Exception $e) {
		send('err', 'Ошибка парсинга файла: '.$e->getMessage());
	}


	$data['text'] = $text;
	$data['addDate'] = $addDate;
}
// ==============================================================


// Запрос в базу
try {
	$id = $db->insert($migration, $data);
} catch(Exception $e) {
	send('err', 'Exception: '.$e->getMessage());
}

if ($id)
	send('ok', 'id: '.$id);
else
	send('err', 'DB error: '.$db->getLastError());



