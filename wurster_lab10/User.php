<?php
namespace wurster\login;
use PDO;

Class User {

	private $uid;
	private $username;
	private $password;

	private const DB_CONNECT = 'sqlite:pwsalt.db';

	public function __construct($uid, $username, $password) {
		$this->uid = $uid;
		$this->username = $username;
		$this->password = $password;
	}

	public function getUid() {
		return $this->uid;
	}
	public function getUsername() {
		return $this->username;
	}
	public function getPassword() {
		return $this->password;
	}

	static function connect() {
		return new PDO(User::DB_CONNECT);
	}

	static function raw_login($username, $password) {
		$db = User::connect();
		$stmt = $db->prepare("select * from user where username = :username");
		$stmt->execute([':username' => $username]);
		
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$md5 = substr($password, 60);

		if(!$user || !password_verify($md5, $user['password'])) {
			return NULL;
		}else{
			return new User($user['uid'], $user['username'], $user['password']);
		}
	}

	static function id_login($uid) {
		$db = User::connect();
		$stmt = $db->prepare("select * from user where uid = :uid");
		$stmt->execute([':uid' => $uid]);

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!$user) {
			return NULL;
		}else{
			return new User($user['uid'], $user['username'], $user['password']);
		}

	}

	static function register($username, $password) {
		$db = User::connect();
		$stmt = $db->prepare("insert into user values (NULL, :username, :password)");
		$stmt->execute([
			':username' => $username,
			':password' => substr($password, 0, 60)			
			]);
	}

	//returns true when tested val is NOT in the chosen attr column
	static function chkAvail($attr, $val) {
		$db = User::connect();
		$stmt = $db->prepare("select " . $attr . " from user");
		$stmt->execute();
		$attArr = $stmt->fetchall(PDO::FETCH_COLUMN);
		return !in_array($val, $attArr);
	}




}
