<?php

// send('err', 'err');

// ini_set('display_errors', 1);

require_once '../inc/conf.php';
require_once '../inc/db.php';
require_once '../classes/db.php';


// $protocol = ($_SERVER['REQUEST_SCHEME'] == 'http') ? 'http://' : 'https://';
// $url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
// $url = parse_url($url);

// send('err', $_POST);

function send($status, $data) {
	die(json_encode(['status'=> $status, 'data'=> $data]));
}


$data = new db($db);

$migration = $data->getVar($_POST['migration']);
$order = $data->getVar($_POST['order']);


if ($migration == '') {
	send('err', 'migration not valid');
}


if ($migration == 'procedure') {

	$s1 = $data->getVar($_POST['formData']['s1']);
	$s2 = $data->getVar($_POST['formData']['s2']);

	if ($s1 != '' && $s2 == '') {
		$res = $data->call('getDipByFirst', [$s1, 0, $cfg['rowsPerPage']]);
	} elseif ($s1 == '' && $s2 != '') {
		$res = $data->call('getDipBySecond', [$s2, 0, $cfg['rowsPerPage']]);
	} elseif ($s1 != '' && $s2 != '') {
		$res = $data->call('getDipByBoth', [$s1, $s2, 0, $cfg['rowsPerPage']]);
	} else {
		send('err', 'both students are invalid '.$s1.$s2);
	}

} else {
	$res = $data->select($migration, $order);
}

if ($res['errNo'] != 0) {
	send('err', 'DB error: '.$res['errMsg']);
}

if ($res['res']->num_rows > 0) {
	while ($row = $res['res']->fetch_assoc()) {
		$a[] = $row;
	}

	send('ok', $a);
} else {
	send('err', 'rows not found');
}

send('err', 'err');
