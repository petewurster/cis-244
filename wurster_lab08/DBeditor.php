<?php
namespace wurster\museums;
require_once 'head.php';
use PDO;

Class DBeditor{
	private $db;
	function __construct() {
		$this->db = new PDO(DB_CONNECT);
	}

	public function addRow($city, $country, $museum) {
		$qy = 'insert into country (c_name) values ("' . $country . '")';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();

		$qy = 'select cid from country where country.c_name ="' . $country . '"';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();
		$cid = $stmt->fetch();

		$qy = 'insert into city (city_name, c_code) values ("' . $city . '", ' . $cid['cid'] . ')';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();

		$qy = 'select city.cid from city where c_code =' . $cid['cid'] .' and city_name ="' . $city . '"';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();
		$cityid = $stmt->fetch();

		$qy = 'insert into museum (m_name, c_code, city_code) values ("' . $museum . '", ' . $cid['cid'] . ', ' . $cityid['cid'] . ')';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();
	}

	public function delRow($rowid) {
		$qy = 'delete from museum where mid = ' . $rowid;
		$stmt = $this->db->prepare($qy);
		$stmt->execute();
	}

	public function modRow($rowid, $newname) {
		$qy = 'update museum set m_name = "' . $newname . '" where mid = ' . $rowid;
		$stmt = $this->db->prepare($qy);
		$stmt->execute();
	}


	public function makeDB() {
		$qy = 'create table country(cid integer primary key, c_name text unique)';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();

		$qy = 'create table city(cid integer primary key, city_name text, c_code int, foreign key (c_code) references country(cid))';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();

		$qy = 'create table museum(mid integer primary key, m_name text, c_code int, city_code int, foreign key (c_code) references country(cid), foreign key (city_code) references city(cid))';
		$stmt = $this->db->prepare($qy);
		$stmt->execute();

		//extract data from flat files to iteratively build tables
		$mclines = explode('+', file_get_contents(SOURCE_FILE));
		$clines = explode('+', file_get_contents(CITY_SOURCE_FILE));
		$fillmuseum = 'insert into museum (m_name, c_code, city_code) values ';
		$fillcity = 'insert into city (city_name, c_code) values ';
		foreach($mclines as $k => $v) {
			$mclines[$k] = trim($v);
			$mcentry = explode('  --  ', $mclines[$k]);
			$this->addRow(trim($clines[$k]), $mcentry[1], $mcentry[0]);
		}
	}
}