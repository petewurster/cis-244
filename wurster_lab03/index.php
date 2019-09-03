<?php

$navs = [
	'page1' => 'p_cubensis',
	'page2' => 'p_tampanensis',
	'page3' => 'a_muscaria'
];

$pageData = [
	'p_cubensis' =>	[
		'image' => 'p_cubensis.jpg',
		'text' => [
			'p_cubensis fact 1 of 2',
			'p_cubensis fact 2 of 2'
		]
	],
	'p_tampanensis' => [
		'image' => 'p_tampanensis.jpg',
		'text' => [
			'p_tampanensis fact 1 of 3',
			'p_tampanensis fact 2 of 3',
			'p_tampanensis fact 3 of 3'
		]
	],
	'a_muscaria' =>	[
		'image' => 'a_muscaria.jpg',
		'text' => [
			'a_muscaria fact 1 of 4',
			'a_muscaria fact 2 of 4',
			'a_muscaria fact 3 of 4',
			'a_muscaria fact 4 of 4'
		]
	]
];

$page = $_GET['show'] ?? 'mushrooms';

Class Page {
	public $head = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" type="text/css" href="./css/style.css"><link rel="icon" type="image/ico" href="./images/hat2.png"><title>';
	public $page;
	public $h1;
	public $li = "\n";
	public $main = '<main><h2>Select a strain to explore!</h2>' . "\n";
	public $foot = '<footer><p>2019 mushroom guide</p></footer>';
	//called to set init values
	public function __construct($navs, $pageData, $page) {
		$this->page = $page;
		$this->h1 = strtoupper($page);
		$this->head .= $this->h1 . '</title></head><body>';
		foreach($navs as $k => $v) {
			$this->li .= '<li><a href="index.php?show=' . $v .'">' . $v . '</a></li>' . "\n";
		}
		//re-build main as needed
		if($page !== 'mushrooms') {
			$this->main = '<main class="full"><aside><img src="./images/'. $pageData[$page]['image'] .'" alt="picture of ' . $page . '"></aside>';
			foreach($pageData[$page]['text'] as $p) {
				$this->main .= '<p>' . $p . '</p>' . "\n";
			}
		}
	}
	//call to create page
	public function displayPage() {
		echo $this->head;
		echo '<div id="outside">';
		echo '<header><h1>' . $this->h1 . '</h1></header>';
		echo '<nav><ul>' . $this->li . '</ul></nav>';
		echo $this->main . '</main>';
		echo $this->foot;
		echo '</div></body></html>';
	}
}

//create new page object & display
$test = new Page($navs, $pageData, $page); 
$test->displayPage();

?>