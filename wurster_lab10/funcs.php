<?php
namespace wurster\login;
require_once 'head.php';
require_once 'funcs.php';
// require_once 'User.php';

//prep POST for validation
function stripPost() {
	$data = [
		':username' => $_POST['username'] ?? null,
		':password' => $_POST['password'] ?? null,
		':confirm' => $_POST['confirm'] ?? null,
		'error' => []
	];
		foreach($data as $k => $v) {
		if($v === null) {
			unset($data[$k]);
		}
	}
	//send for validation before returning userinput
	return validate($data);
}



function validate($data) {






	return $data;
}

function san($val) {
	return htmlentities($val);
}

function loginTest() {
    return isset($_SESSION['uid']) && ((int) $_SESSION['uid']) > 0;
}

function chkCreds() {
	if(isset($_POST['login'])) {
		$user = User::raw_login($_POST['username'], gethashed($_POST['username'], $_POST['password']));
		//assume non-match to be a new user
		if(isset($_POST['confirm'])){
			//warn about duplicate usernames in db
			if(!User::chkAvail('username', $_POST['username'])) {
				unset($_POST['username'], $_POST['password']);
				echo "<script>alert('username is taken');</script>";
			}else{
				//warn new user that password didn't match confirmation
				if($_POST['confirm'] !== $_POST['password']) {
					unset($_POST['confirm'], $_POST['password']);
					echo "<script>alert('passwords do not match');</script>";
				}else{
					//create new user & login
					User::register($_POST['username'], gethashed($_POST['username'], $_POST['password']));
					$user = User::raw_login($_POST['username'], gethashed($_POST['username'], $_POST['password']));
				}
			}
		}
		//login successful
		if($user !== NULL) {
			$_SESSION['uid'] = $user->getUid();
			$_SESSION['xCRSF'] = bin2hex(random_bytes(32));
			header('Location: home.php');
			exit;
		}
	}
}

function gethashed($username, $password) {
	$pepper = md5($username . $password . strrev($username));
	return password_hash($pepper, PASSWORD_DEFAULT) . $pepper;
}