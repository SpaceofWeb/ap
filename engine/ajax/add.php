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
$db->setPrefix($cfg['dbprefix']);


//////////////////////
// Получение данных //
//////////////////////
if (!isset($_POST['migration']) || $_POST['migration'] == '') {
	send('err', 'таблица не валидна');
}
$migration = $_POST['migration'];


$data = [];
foreach ($_POST as $key => $value) {
	if ($key == 'migration' || $key == 'id' || $key == 'file') continue;
	$data[$key] = $value;
}

$pfx = $cfg['dbprefix'];
// ==============================================================


////////////////////////////
// Если мы обновляем файл //
////////////////////////////
if (isset($_FILES['file']) && $migration == 'diplomas') {

	if ($_FILES['file']['error']) {
		send('err', 'Ошибка загрузки файла: '.$_FILES['file']['error']);
	}

	// Проверяем есть ли дипломная у данного студента
	$db->where('student_id', $data['student_id']);
	$res = $db->get('diplomas', null, ['id']);

	if ($db->getLastErrno() !== 0) {
		send('err', 'DB error: '.$db->getLastError());
	}

	if ($db->count != 0) {
		send('err', 'у данного студента уже есть дипломная работа');
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


	$data['text'] = $db->escape(strip_tags(trim($text)));
	$data['file'] = $file;
	$data['addDate'] = $addDate;
}
// ==============================================================


// Запрос в базу
try {
	$id = $db->insert($migration, $data);
} catch(Exception $e) {
	send('err', 'DB Exception: '.$e->getMessage());
}

if ($id) {
	if ($migration == 'diplomas') {
		$q = "INSERT INTO {$pfx}percentage (d1_id, d2_id)
					SELECT ?, D2.id 
					FROM {$pfx}diplomas D2 
					LEFT JOIN {$pfx}diplomas D ON D.id=? 
					LEFT JOIN {$pfx}students S ON S.id=D.student_id 
					LEFT JOIN {$pfx}students S2 ON S2.id=D2.student_id 
					WHERE D2.id < ? AND S.group_id=S2.group_id";

		try {
			$db->rawQuery($q, [$id, $id, $id]);
			if (!$db->getLastErrno()) {
				exec('node ../compare/compare.js > /dev/null &');
				send('ok', 'Запись успешно добавлена!');
			} else {
				send('err', 'DB error: '.$db->getLastError());
			}
		} catch(Exception $e) {
			send('err', 'DB Exception: '.$e->getMessage());
		}
	}

	send('ok', 'Запись успешно добавлена!');
} else
	send('err', 'DB error: '.$db->getLastError());



