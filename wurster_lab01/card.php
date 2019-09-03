<?php require 'head.html' ?>

<body>

<?php

//set constants
define('ADDR', '<h5>1700 Spring Garden St.</h5>');
define('CITY_ST', '<h5>Philadelphia PA 19130</h5>');

//incorporate POST data into html tags
$username = '<h1>' . htmlentities($_POST['username'], ENT_COMPAT) . '</h1>';
$useremail = '<p><a href="mailto:' . htmlentities($_POST['useremail'], ENT_COMPAT) . '@student.ccp.edu">' . htmlentities($_POST['useremail'], ENT_COMPAT) . '@student.ccp.edu</a></p>';

//define Class
class BusinessCard {
	public $name;
	public $title = '<h2>Student Extraordinaire</h2>';
	public $course = '<h3><em>CIS-244</em></h3>';
	public $school = '<h4>Community College of Philadelphia</h4>';
	private $addr = ADDR;
	private $citySt = CITY_ST;
	public $link;

	//include POST data
	public function __construct($username, $useremail) {
		$this->name = $username;
		$this->link = $useremail;
	}

	//getter to display string representation of the object
	function display() {
		foreach($this as $prop => $val) {
			echo $val;
		}
	}
}

//create instance of the business card
$pete = new BusinessCard($username, $useremail);

?>

<div id="card">

<?php 

//display results
$pete->display();

?>

</div>


</body>
</html>