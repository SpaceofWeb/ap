<?php

// send('err', 'err');

// ini_set('display_errors', 1);

require_once '../inc/conf.php';
require_once '../inc/db.php';
require_once '../classes/db.php';
require_once '../classes/docx2text.php';


// $protocol = ($_SERVER['REQUEST_SCHEME'] == 'http') ? 'http://' : 'https://';
// $url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
// $url = parse_url($url);


function send($status, $data='') {
	die(json_encode(['status'=> $status, 'data'=> $data]));
}

// send('err', [$_POST, $_FILES]);

$data = new db($cfg, $db);

$migration = $data->getVar($_POST['migration']);
if ($migration == '') {
	send('err', 'migration not valid');
}



// If we adding diplomas
if (isset($_FILES['file']) && $migration == 'diplomas') {

	if ($_FILES['file']['error']) {
		send('err', 'File upload error: '.$_FILES['file']['error']);
	}

	foreach ($_POST as $key => $value) {
		if ($key == 'migration') continue;
		if ($key == 'student_id') $student_id = $data->getVar($value);
		if ($key == 'year') $year = $data->getVar($value);
		$formData[] = ['name'=> $key, 'value'=> $value];
	}

	// Check if diploma exists in db
	$res = $data->selectQuery("SELECT id FROM ap_diplomas WHERE student_id='{$student_id}' ");
	if ($res['errNo'] != 0) {
		send('err', 'DB error: '.$res['errMsg']);
	}

	if ($res['res']->num_rows != 0) {
		send('err', 'diploma already exists');
	}

	// Save diploma
	$file = 'diplomas/';

	if (!file_exists($cfg['uploadDir'].$file)) {
		mkdir($cfg['uploadDir'].$file, 0777, true);
		chmod($cfg['uploadDir'].$file, 0777);
	}

	$file .= $year.'/';

	if (!file_exists($cfg['uploadDir'].$file)) {
		mkdir($cfg['uploadDir'].$file, 0777, true);
		chmod($cfg['uploadDir'].$file, 0777);
	}


	if (!is_writable($cfg['uploadDir'].$file)) {
		send('err', 'Необходимо выставить права 777 на деректорию "'.$cfg['uploadDir'].'"');
	}

	$addDate = mktime();
	$file .= 'sid'.$student_id.'.docx';

	if (!move_uploaded_file($_FILES['file']['tmp_name'], $cfg['uploadDir'].$file)) {
		send('err', 'Не удалось сохранить файл');
	}

	// Try parse file
	try {
		$d2t = new DocumentParser();
		$text = $d2t->parseFromFile($cfg['uploadDir'].$file, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
	} catch(Exeption $e) {
		send('err', 'Ошибка парсинга файла');
	}

	$text = $data->escapeVar($text, []);

	$formData[] = ['name'=> 'text', 'value'=> $text];
	$formData[] = ['name'=> 'addDate', 'value'=> $addDate];
	$formData[] = ['name'=> 'file', 'value'=> $file];

} else {
	$formData = $_POST['formData'];
}



for ($i=0; $i < count($formData); $i++) { 
	$formData[$i]['name'] = $data->getVar($formData[$i]['name']);
	$formData[$i]['value'] = $data->getVar($formData[$i]['value']);

	if ($formData[$i]['name'] == '')
		send('err', 'form data not valid');
}



$res = $data->insert($migration, $formData);
if ($res['errNo'] != 0) {
	send('err', 'DB error: '.$res['errMsg']."\nquery: ".$res['q']);
} elseif ($res['errNo'] == 0) {
	if ($migration == 'diplomas') {
		$data->call('addPercentRows', [$res['insertId']]);
		exec('node ../compare/compare.js > /dev/null &');
	}

	send('ok');
}


send('err', 'err');
