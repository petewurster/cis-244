<?php

//prep POST for PDO binding/execution
function stripPost() {
	$data = [
		':id' => $_POST['employee_id'] ?? null,
		':fname' => $_POST['first_name'] ?? null,
		':lname' => $_POST['last_name'] ?? null,
		':email' => $_POST['email'] ?? null,
		':ext' => $_POST['extension'] ?? null,
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

//generate error mesages for invalid GET & POST info
function validate($data) {
	if(isset($data[':id'])) {
		if(!chkINT($data[':id']) || !chkMinMax($data[':id'], 1000, 9999)) {
			$data['error'][] = 'Employee ID must be a 4-digit number!';
		}
		if(!chkUNIQUE('employee_id', $data[':id']) && !isset($_POST['update']) && !isset($_POST['delete'])) {
			$data['error'][] = 'Invalid ID entry!';
		}
	}

	if(isset($data[':fname'])) {
		$data[':fname'] = trim($data[':fname']);
		if(!chkSTRlen($data[':fname'], 2, 20)) {
			$data['error'][] = 'First name must be 2-20 characters!';
		}
		if(scanSTR($data[':fname']) !== 0) {
			$data['error'][] = 'First name cannot use numerical characters!';
		}
	}

	if(isset($data[':lname'])) {
		$data[':lname'] = trim($data[':lname']);
		if(!chkSTRlen($data[':lname'], 2, 20)) {
			$data['error'][] = 'Last name must be 2-20 characters!';
		}
		if(scanSTR($data[':lname']) !== 0) {
			$data['error'][] = 'Last name cannot use numerical characters!';
		}
	}

	if(isset($data[':email'])) {
		//check db for 
		$db = new PDO('sqlite:employees.db');
		$qy = "select email from employee where employee_id = :id";
		$stmt = $db->prepare($qy);
		$stmt->execute([$data[':id']]);
		$myemail = $stmt->fetchAll(PDO::FETCH_COLUMN);
		if(empty($myemail)) {
			$myemail[] = 1;
		}
		if(     (!chkEmail($data[':email']) || !chkUNIQUE('email', $data[':email'])) && isset($_GET['employee_id']) || (($data[':email'] === $myemail[0] || $myemail[0] === 1) && isset($_POST['add'])) ){
			$data['error'][] = 'Invalid email entry!';
		}
	}

	if(isset($data[':ext'])) {
		if(scanSTR($data[':ext']) !== 4 && chkSTRlen($data[':ext'], 4, 4)) {
			$data['error'][] = 'Extension must use numeric characters!';
		}
		if(!chkSTRlen($data[':ext'], 4, 4)) {
			$data['error'][] = 'Extension must be 4 digits in length!';
		}
	}

	//prep page to display errors
	if(!empty($data['error'])) {
    	unset($_POST['update'], $_POST['delete'], $_POST['add']);
   	    unset($_GET);
	}
	return $data;
}

function chkINT($val) {
    return filter_var($val, FILTER_VALIDATE_INT);
}

function chkMinMax($val, $min, $max) {
	return $val >= $min && $val <= $max;
}

function chkEmail($val) {
	return filter_var($val, FILTER_VALIDATE_EMAIL);
}

function chkSTRlen($val, $min, $max) {
	return strlen($val) >= $min && strlen($val) <= $max;
}

function scanSTR($val) {
		$count = 0;
		//verifies each character individually as an INT
		foreach(str_split($val) as $k => $v){
			if (chkINT($v) || $v === '0') {
				$count += 1;
			};
		}
		return $count;
}

//disallow duplicate entry for unique data
function chkUNIQUE($key, $val) {
		$db = new PDO('sqlite:employees.db');
		$qy = "select " . $key . " from employee";
		$stmt = $db->prepare($qy);
		$stmt->execute();
		$arr = $stmt->fetchAll(PDO::FETCH_COLUMN);
		return !in_array($val, $arr);
}

//sanitize to ensure echo/print safety
function san($data) {
	return htmlentities($data, ENT_QUOTES);
}