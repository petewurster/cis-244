<?php
namespace mushroom;

function getPageData() {
	return [
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
}

//builds list elements from pageData array
function getNavs() {
	$pageData = getPageData();
	$li = '';
	foreach($pageData as $k => $v) {
		$li .= '<li><a href="' . COVER_PAGE . '?show=' . $k .'">' . $k . '</a></li>' . "\n";
	}
	return $li;
}

//builds document head
function getHead($show) {
	$pageData = getPageData();
	return '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" type="text/css" href="./css/style.css"><link rel="icon" type="image/ico" href="./images/hat2.png"><title>' . strtoupper($show) . '</title></head>';
}

//packeges data to send to Page constructor
function package($show) {
	return [
		'head' => getHead($show),
		'navs' => getNavs(),
		'pageData' => getPageData()
	];
}

?>