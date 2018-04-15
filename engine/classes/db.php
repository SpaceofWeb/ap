<?php

/**
* class for work with data base
*/
class db {
	function __construct($db){
		$this->db = $db;
	}

	public function getVar($var) {
		if (isset($var)) {
			if (is_string($var)) {
				return $this->escapeVar($var);
			} elseif (is_array($var)) {
				return $this->escapeArr($var);
			} elseif (is_int($var)) {
				return $this->escapeInt($var);
			}
		}

		return '';
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

	public function escapeInt($var) {
		return (int)$this->escapeVar($var, []);
	}

	public function buildSelectQuery($mig, $order) {
		$order = $this->buildOrder($order);

		return "SELECT * FROM ap_{$mig} {$order}";
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

	public function buildFormData($formData) {
		$rows = '(';
		$vals = 'VALUES(';

		for ($i=0; $i < count($formData); $i++) {
			$rows .= $formData[$i]['name'].',';

			if (is_int($formData[$i]['value']))
				$vals .= ''.$formData[$i]['value'].',';
			else
				$vals .= '\''.$formData[$i]['value'].'\',';
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

	public function select($mig, $order) {
		$q = $this->buildSelectQuery($mig, $order);

		$res = $this->db->query($q);

		return [
			'errNo'=> $this->db->errno,
			'errMsg'=> $this->db->error,
			'res'=> $res,
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

	public function call($proc, $params) {
		$params = $this->buildProcData($params);

		$res = $this->db->query("CALL {$proc}{$params}");

		return [
			'errNo'=> $this->db->errno,
			'errMsg'=> $this->db->error,
			'res'=> $res,
		];
	}
}