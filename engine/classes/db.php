<?php

/**
* class for work with data base
*/
class db {
	function __construct($cfg, $db){
		$this->cfg = $cfg;
		$this->db = $db;
	}

	public function getVar($var) {
		if (isset($var)) {
			if (is_array($var)) {
				return $this->escapeArr($var);
			} else {
				return $this->escapeVar($var);
			}
		}

		return '';
	}

	public function getInt($var) {
		return (int)$this->escapeVar($var, []);
	}

	public function escapeVar($var, $opts=['trim', 'strip_tags', 'html']) {
		if (in_array('trim', $opts)) $var = trim($var);
		if (in_array('strip_tags', $opts)) $var = strip_tags($var);
		if (in_array('html', $opts)) $var = htmlspecialchars($var);

		return $this->db->real_escape_string($var);
	}

	public function escapeArr($var) {
		for ($i=0; $i < count($var); $i++) {
			$var[$i] = $this->escapeVar($var[$i]);
		}
		return $var;
	}

	public function buildSelectQuery($mig, $order, $limit) {
		$order = $this->buildOrder($order);

		return "SELECT * FROM ap_{$mig} {$order} LIMIT {$limit}, {$this->cfg['rowsPerPage']}";
	}

	public function buildInsertQuery($mig, $formData) {
		list($rows, $vals) = $this->buildFormData($formData);

		return "INSERT INTO ap_{$mig} {$rows} {$vals}";
	}

	public function buildOrder($order) {
		if (is_string($order)) {
			return ($order != '') ? 'ORDER BY '.$order : '';

		} elseif (is_array($order) && count($order) > 0) {

			$ord = 'ORDER BY ';
			for ($i=0; $i < count($order); $i++) { 
				$ord .= $order[$i].',';
			}

			return substr($ord, 0, -1);
		}

		return '';
	}

	public function buildFormData($fd) {
		$rows = '(';
		$vals = 'VALUES(';

		for ($i=0; $i < count($fd); $i++) {
			$rows .= $fd[$i]['name'].',';

			if (is_int($fd[$i]['value']))
				$vals .= ''.$fd[$i]['value'].',';
			else
				$vals .= '\''.$fd[$i]['value'].'\',';
		}

		$rows = substr($rows, 0, -1).')';
		$vals = substr($vals, 0, -1).')';

		return [$rows, $vals];
	}

	public function buildProcData($p) {
		$params = '(';

		for ($i=0; $i < count($p); $i++) {
			$params .= '\''.$p[$i].'\',';
		}

		$params = substr($params, 0, -1).')';

		return $params;
	}

	public function select($mig, $order, $limit) {
		$q = $this->buildSelectQuery($mig, $order, $limit);

		$res = $this->db->query($q);

		return [
			'errNo'=> $this->db->errno,
			'errMsg'=> $this->db->error,
			'res'=> $res,
			'q'=> $q,
		];
	}

	public function insert($mig, $formData) {
		$q = $this->buildInsertQuery($mig, $formData);

		$this->db->query($q);

		return [
			'errNo'=> $this->db->errno,
			'errMsg'=> $this->db->error,
			'insertId'=> $this->db->insert_id,
			'q'=> $q,
		];
	}

	public function selectQuery($q) {
		$res = $this->db->query($q);

		return [
			'errNo'=> $this->db->errno,
			'errMsg'=> $this->db->error,
			'res'=> $res,
		];
	}

	public function call($proc, $params=NULL) {
		if ($params !== NULL) {
			$params = $this->buildProcData($params);

			$res = $this->db->query("CALL {$proc}{$params}");
		} else {
			$res = $this->db->query("CALL {$proc}");
		}

		return [
			'errNo'=> $this->db->errno,
			'errMsg'=> $this->db->error,
			'res'=> $res,
		];
	}
}