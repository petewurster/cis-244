<?php
namespace Page;

Class Page {
	public $bodyDiv = '<body><div id="outside">' . "\n";
	public $divBody = '</div></body></html>';
	public $nav = "\n";
	public $main = '<main>' . "\n" . '<h2>Select a strain to explore!</h2>' . "\n";
	public $foot = '<footer><p>2019 mushroom guide</p></footer>' . "\n";
	//called to set variable init values
	public function __construct($package, $show) {
		$this->head = $package['head'];
		$this->page = $show;
		$this->h1 = '<header><h1>' . strtoupper($show) . '</h1></header>';
		$this->nav .= '<nav><ul>' . "\n" . $package['navs'] . '</ul></nav>' . "\n";
		//re-build main as needed
		if($show !== 'mushrooms') {
			$this->main = '<main class="full">' . "\n" . '<aside><img src="./images/'. $package['pageData'][$show]['image'] .'" alt="picture of ' . $show . '"></aside>' . "\n";
			foreach($package['pageData'][$show]['text'] as $p) {
				$this->main .= '<p>' . $p . '</p>' . "\n";
			}
		}
		$this->main .= '</main>' . "\n";
	}

	//call to create page
	public function displayPage() {
		echo $this->head;
		echo $this->bodyDiv;
		echo $this->h1;
		echo $this->nav;
		echo $this->main;
		echo $this->foot;
		echo $this->divBody;
	}
}

?>