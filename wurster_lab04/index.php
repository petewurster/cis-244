<?php
namespace mushroom;

require_once('constants.php');
require_once(PAGE_DATA);
require_once(CLASS_PAGE);

use Page\Page;

//set default if no GET is present
$show = $_GET['show'] ?? 'mushrooms';

//create and display page object
$test = new Page(package($show), $show); 
$test->displayPage();

?>