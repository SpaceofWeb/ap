<?php

// Выборка из базы

// ini_set('display_errors', 1);

require_once '../inc/conf.php';
// require_once '../inc/db.php';
require_once '../classes/mysqliDB.php';
require_once '../classes/docx2text.php';


function send($status, $data=[], $info=[]) {
	die(json_encode(['status'=> $status, 'data'=> $data, 'info'=> $info]));
}


$db = new MysqliDb($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpass'], $cfg['dbname']);
$db->setPrefix('ap_');


/////////////////////////////////////
// Если запро один из тех что ниже //
/////////////////////////////////////
if (isset($_POST['raw']) && $_POST['raw'] == true) {
	if (!isset($_POST['by']) || $_POST['by'] == '' ||
			!isset($_POST['limit']) || $_POST['limit'] == '' ||
			!isset($_POST['s1']) || !isset($_POST['s2'])) {

		send('err', 'Отправленные данные не валидны', $_POST);
	}

	$by = $_POST['by'];
	$limit = $_POST['limit'];
	$s1 = $_POST['s1'];
	$s2 = $_POST['s2'];

	// Выбираем по первому студенту
	if ($by == 'str' && $s1 != '' && $s2 == '') {

		$q = "SELECT P.percent,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s1,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s2
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
						AND P.percent IS NOT NULL
					UNION SELECT P.percent,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s1,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s2
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%')
						AND P.percent IS NOT NULL
					ORDER BY percent DESC
					LIMIT ?, ?";

		$params = [$s1, $s1, ($limit-1)*$cfg['rowsPerPage'], $cfg['rowsPerPage']];

		$qCount = "SELECT COUNT(P.id) AS count
							FROM ap_percentage P
								LEFT JOIN ap_diplomas D ON D.id=P.d1_id
								LEFT JOIN ap_students S ON S.id=D.student_id
								LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
								LEFT JOIN ap_students S2 ON S2.id=D2.student_id
							WHERE (CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
								OR CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%'))
								AND P.percent IS NOT NULL
							ORDER BY percent DESC";

		$paramsCount = [$s1, $s1];

	// Выбираем пл второму студенту
	} elseif ($by == 'str' && $s1 == '' && $s2 != '') {

		$q = "SELECT P.percent,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s2,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s1
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
						AND P.percent IS NOT NULL
					UNION SELECT P.percent,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s2,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s1
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%')
						AND P.percent IS NOT NULL
					ORDER BY percent DESC
					LIMIT ?, ?";

		$params = [$s2, $s2, ($limit-1)*$cfg['rowsPerPage'], $cfg['rowsPerPage']];

		$qCount = "SELECT COUNT(P.id) AS count
							FROM ap_percentage P
								LEFT JOIN ap_diplomas D ON D.id=P.d1_id
								LEFT JOIN ap_students S ON S.id=D.student_id
								LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
								LEFT JOIN ap_students S2 ON S2.id=D2.student_id
							WHERE (CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
								OR CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%'))
								AND P.percent IS NOT NULL
							ORDER BY percent DESC";

		$paramsCount = [$s2, $s2];

	// Выборка по двум студентам
	} elseif ($by == 'str' && $s1 != '' && $s2 != '') {

		$q = "SELECT P.percent,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s1,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s2
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE (CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
						AND CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%'))
						AND P.percent IS NOT NULL
					UNION SELECT P.percent,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s1,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s2
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE (CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
						AND CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%'))
						AND P.percent IS NOT NULL
					ORDER BY percent DESC
					LIMIT ?, ?";

		$params = [$s1, $s2, $s2, $s1, ($limit-1)*$cfg['rowsPerPage'], $cfg['rowsPerPage']];

		$qCount = "SELECT COUNT(P.id) AS count
							FROM ap_percentage P
								LEFT JOIN ap_diplomas D ON D.id=P.d1_id
								LEFT JOIN ap_students S ON S.id=D.student_id
								LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
								LEFT JOIN ap_students S2 ON S2.id=D2.student_id
							WHERE ((CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
									AND CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%'))
							    OR (CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE CONCAT('%?%')
									AND CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) LIKE CONCAT('%?%')))
								AND P.percent IS NOT NULL
							ORDER BY percent DESC";

		$paramsCount = [$s1, $s2, $s2, $s1];

	// Выборка по двум студентам по id
	} elseif ($by == 'id' && $s1 != '' && $s2 != '') {

		$q = "SELECT P.percent,
						CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) AS s1,
						CONCAT(S2.firstName, ' ', S2.middleName, ' ', S2.lastName) AS s2,
						D.text AS d1, D2.text AS d2
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE ((P.d1_id=?
							AND P.d2_id=?)
						OR (P.d1_id=?
							AND P.d2_id=?))
						AND P.percent IS NOT NULL
					ORDER BY percent DESC";

		$params = [$s1, $s2, $s2, $s1];

	// Выборка всех студентов
	} else {

		$q = "SELECT P.percent,
						CONCAT(S.firstName,' ',S.middleName,' ',S.lastName) AS s1,
						CONCAT(S2.firstName,' ',S2.middleName,' ',S2.lastName) AS s2
					FROM ap_percentage P
						LEFT JOIN ap_diplomas D ON D.id=P.d1_id
						LEFT JOIN ap_students S ON S.id=D.student_id
						LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id
						LEFT JOIN ap_students S2 ON S2.id=D2.student_id
					WHERE P.percent IS NOT NULL
					ORDER BY percent DESC
					LIMIT ?, ?";

		$params = [($limit-1)*$cfg['rowsPerPage'], $cfg['rowsPerPage']];

		$qCount = "SELECT COUNT(id) AS rowsCount
							FROM ap_percentage P
							WHERE percent IS NOT NULL
							LIMIT 1";

		$paramsCount = [];
	}


	// Выполняем запрос
	try {
		$rows = $db->rawQuery($q, $params);
		$rowsCount = $db->count;
		if (isset($qCount)) {
			if (count($paramsCount) > 0)
				$tolalCount = $db->rawQueryOne($qCount, $paramsCount)['rowsCount'];
			else
				$tolalCount = $db->rawQueryOne($qCount)['rowsCount'];
		} else $tolalCount = 1;
	} catch(Exception $e) {
		send('err', 'Exception: '.$e->getMessage());
	}

	if ($rowsCount > 0)
		send('ok', $rows, ['count'=> ceil($tolalCount/$cfg['rowsPerPage']), 'limit'=> $limit]);
	else
		send('ok', [], ['count'=> 1, 'msg'=> 'rows not found']);
}
// ==============================================================




////////////////////////////////////
// Если запрос надо сгенерировать //
////////////////////////////////////
if (!isset($_POST['migration']) || $_POST['migration'] == '') {
	send('err', 'migration not valid');
}
$migration = $_POST['migration'];



$search = $_POST['search'];
$cols = ($_POST['cols']) ? $_POST['cols'] : null;
$join = $_POST['join'];
$order = $_POST['order'];
$limit = (int)$_POST['limit'];


// Генерируем запрос
for ($i=0; $i < count($join); $i++) {
	$j = $join[$i];

	if (isset($j['table']) && isset($j['type']) && isset($j['cond'])) {
		$db->join($j['table'], $j['cond'], $j['type']);
	}
}



for ($i=0; $i < count($search); $i++) {
	$s = $search[$i];
	$s['val'] = ($s['val'] == 'null') ? NULL : $s['val'];

	if (isset($s['key']) && isset($s['val'])) {
		if (isset($s['cond'])) {
			if (isset($s['or']) && $s['or']) {
				$db->orWhere($s['key'], $s['val'], $s['cond']);
			} else {
				$db->where($s['key'], $s['val'], $s['cond']);
			}
		} else {
			if (isset($s['or']) && $s['or']) {
				$db->orWhere($s['key'], $s['val']);
			} else {
				$db->where($s['key'], $s['val']);
			}
		}
	} elseif (isset($s['raw'])) {
		$db->where($s['raw']);
	}
}


if ($limit > 0) {
	$limit = [
		($limit-1)*$cfg['rowsPerPage'],
		$cfg['rowsPerPage']
	];
} else {
	$limit = null;
}


for ($i=0; $i < count($order); $i++) {
	$db->orderBy($order[$i]['col'], $order[$i]['sort']);
}


// Выполняем запрос
try {
	$rows = $db->withTotalCount()->get($migration, $limit, $cols);
} catch(Exception $e) {
	send('err', 'Exception: '.$e->getMessage());
}

if ($db->count > 0)
	send('ok', $rows, ['count'=> ceil($db->totalCount/$cfg['rowsPerPage']), 'limit'=> $limit]);
else
	send('ok', [], ['count'=> 1, 'msg'=> 'rows not found']);
// ==============================================================



