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

function send($status, $data=[], $info=[]) {
	die(json_encode(['status'=> $status, 'data'=> $data, 'info'=> $info]));
}


$data = new db($cfg, $db);

$migration = $data->getVar($_POST['migration']);
$order = $data->getVar($_POST['order']);
$limit = $data->getInt($_POST['limit']);

$limit = ($limit == '') ? 0 : ($limit-1)*$cfg['rowsPerPage'];


if ($migration == '') {
	send('err', 'migration not valid');
}


if ($migration == 'procedure') {

	$s1 = $data->getVar($_POST['formData']['s1']);
	$s2 = $data->getVar($_POST['formData']['s2']);

	if ($s1 != '' && $s2 == '') {

		$res = $data->call('getDipByFirst', [$s1, $limit, $cfg['rowsPerPage']]);
		$data->db->next_result();
		$res['count'] = $data->call('getDipByFirstCount', [$s1])
										['res']->fetch_assoc()['count'];

	} elseif ($s1 == '' && $s2 != '') {

		$res = $data->call('getDipBySecond', [$s2, $limit, $cfg['rowsPerPage']]);
		$data->db->next_result();
		$res['count'] = $data->call('getDipBySecondCount', [$s2])
										['res']->fetch_assoc()['count'];

	} elseif ($s1 != '' && $s2 != '') {

		$res = $data->call('getDipByBoth', [$s1, $s2, $limit, $cfg['rowsPerPage']]);
		$data->db->next_result();
		$res['count'] = $data->call('getDipByBothCount', [$s1, $s2])
										['res']->fetch_assoc()['count'];

	} else {

		$res = $data->call('getDip', [$limit, $cfg['rowsPerPage']]);
		$data->db->next_result();
		$res['count'] = $data->call('getDipCount')
										['res']->fetch_assoc()['count'];
	}

} else {
	$res = $data->select($migration, $order, $limit);
}


if ($res['errNo'] != 0) {
	send('err', 'DB error: '.$res['errMsg']);
}

if ($res['res']->num_rows > 0) {
	// $a = $res;
	$a = [];
	while ($row = $res['res']->fetch_assoc()) {
		$a[] = $row;
	}

	send('ok', $a, ['count'=> round($res['count']/$cfg['rowsPerPage'])]);
} else {
	send('err', 'rows not found');
}

send('err', 'err');
