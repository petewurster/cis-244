<?php
namespace pw\trades;
use PDO;

Class User {

	private $uid;
	private $username;
	private $password;

	private const DB_CONNECT = 'sqlite:trades.db';

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

	public static function connect() {
		return new PDO(User::DB_CONNECT);
	}

	public static function raw_login($username, $password) {
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

	public static function id_login($uid) {
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

	public static function register($username, $password) {
		$db = User::connect();
		$stmt = $db->prepare("insert into user values (NULL, :username, :password)");
		$stmt->execute([
			':username' => $username,
			':password' => substr($password, 0, 60)			
			]);
	}

	//returns true when tested val is NOT in the chosen attr column
	public static function chkAvail($attr, $val) {
		$db = User::connect();
		$stmt = $db->prepare("select " . $attr . " from user");
		$stmt->execute();
		$attArr = $stmt->fetchall(PDO::FETCH_COLUMN);
		return !in_array($val, $attArr);
	}

	public static function chkCreds() {
		if(isset($_POST['login'])) {
			$user = User::raw_login($_POST['username'], User::gethashed($_POST['username'], $_POST['password']));
			//assume non-match to be a new user
			if(isset($_POST['confirm'])){
				//warn about duplicate usernames in db
				if(!User::chkAvail('username', $_POST['username']) || !User::chkEmail($_POST['username'])) {
					unset($_POST['username'], $_POST['password']);
					echo "<script>alert('invalid username');</script>";
				}else{
					//warn new user that password didn't match confirmation
					if($_POST['confirm'] !== $_POST['password']) {
						unset($_POST['confirm'], $_POST['password']);
						echo "<script>alert('passwords do not match');</script>";
					}else{
						//create new user & login
						User::register($_POST['username'], User::gethashed($_POST['username'], $_POST['password']));
						$user = User::raw_login($_POST['username'], User::gethashed($_POST['username'], $_POST['password']));
					}
				}
			}
			//login successful
			if($user !== NULL) {
				$_SESSION['uid'] = $user->getUid();
				$_SESSION['xCSRF'] = bin2hex(random_bytes(32));
				header('Location: home.php');
				exit;
			}
		}
	}

	public static function loginTest() {
	    return isset($_SESSION['uid']) && ((int) $_SESSION['uid']) > 0;
	}


	public static function gethashed($username, $password) {
		$pepper = md5($username . $password . strrev($username));
		return password_hash($pepper, PASSWORD_DEFAULT) . $pepper;
	}

	public static function chkEmail($val) {
		if($val === 'admin') {
			return TRUE;
		}
		return filter_var($val, FILTER_VALIDATE_EMAIL);
	}

	public static function validateSession() {
		session_start();
		if(isset($_POST['logout'])) {
			$_SESSION = [];
			setcookie(session_name(), '', time() - 86400, '/');
			if (session_status() === PHP_SESSION_ACTIVE) {
		    	session_destroy();
			}
			header('Location: index.php');
			die;
		}
		if(User::loginTest()) {
			return User::id_login($_SESSION['uid']);
		}else{
			header('Location: index.php');
			die;
		}	
	}


}
