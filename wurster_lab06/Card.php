<?php
namespace wurster\BusinessCard\demo;

define('CARD', '<div class="card ');
define('DRAC', '</div>');

//define Class
class Card {
	//expects userdata array
	public function __construct($userData, $sampleNumber) {
		$this->card = CARD . $sampleNumber .'">';
		foreach($userData as $k => $v) {
			$this->$k = '<span class="' . $k . '" id="' . $k . $sampleNumber . '">' . $v . '</span>';
		}
		$this->drac = DRAC;
	}
	//getter to display string representation of the object
	function display() {
		foreach($this as $prop => $val) {
			echo $val;
		}
	}
}


?>