<?php
namespace pw\trades;
use \pw\trades\User;
use PDO;

Class Book{

	private $bid;
	private $owner;
	private $title;
	private $cond;
	private $isbn;
	private $want;

	private const LIMIT = 5;

	public function __construct($bid, $user, $title, $cond, $isbn, $want) {
		$this->bid = $bid;
		$this->owner = $user;
		$this->title = $title;
		$this->cond = $cond;
		$this->isbn = $isbn;
		$this->want = $want;
			
	}

	public function getOwner() {
		return $this->owner;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getCond() {
		return $this->cond;
	}

	public function getISBN() {
		return $this->isbn;
	}

	public function getBid() {
		return $this->bid;
	}

	public function getWant() {
		return $this->want;
	}

	public function getWantString() {
		$db = User::connect();
		$stmt = $db->prepare("select title as wants from book where bid = :want");
		$stmt->execute([':want' => $this->want]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data['wants'];
	}
	
	public static function getBooksByUser($user) {
		$db = User::connect();
		$stmt = $db->prepare("select * from book where owner = :user");
		//if user value is negative, get everybody else's books instead
		if($user < 0) {
			$stmt = $db->prepare("select * from book where owner <> :user");
			$user *= -1;
		}
		$stmt->execute([':user' => $user]);
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$books = [];
		//create Book objs out of stdClass objs
		foreach($data as $x) {
			$books[] = new Book($x->bid, $user, $x->title, $x->cond, $x->isbn, $x->want);
		}
		return $books;
	}

	public static function findmatch($trade, $lim = Book::LIMIT) {
		$db = User::connect();
		$stmt = $db->prepare("select * from book where bid = :want");
		$stmt->execute([':want' => $trade[count($trade)-1]->getWant()]);
		$next = $stmt->fetch(PDO::FETCH_ASSOC);
		//escape recursion at limit or when no matches are found
		if($next === FALSE || $lim === 0) {
			return NULL;
		}
		//append nextbook to array of books involved in the trade
		$trade[] = new Book($next['bid'], $next['owner'], $next['title'], $next['cond'], $next['isbn'], $next['want']);
		//check to see if trade is satisfied
		if($trade[0]->getBid() === $trade[count($trade)-1]->getWant()) {
			return $trade;
		}
		//search again
		return Book::findmatch($trade, $lim-1);

	}

	public static function buildMatchTables() {
		$tables = [];
		$db = User::connect();
		$stmt = $db->prepare("select * from book");
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		//create Book objs out of stdClass objs
		foreach($data as $x) {
			//use time to create unique table names
			$t = microtime(TRUE);
			$t = str_replace('.', '_', $t);
			$book = new Book($x->bid, $x->owner, $x->title, $x->cond, $x->isbn, $x->want);
			$match = Book::findmatch([$book]);
			if($match) {
				$tables['match' . $t] = $match;
				$stmt = $db->prepare("create table match" . $t . " (
					'bid'	integer,
					'owner' integer,
					'title' text,
					'cond' text,
					'isbn' text,
					'want' integer,
					PRIMARY KEY('bid'))");
				$stmt->execute();
				//build a table showing all books involved in each trade
				foreach($match as $y){
					$stmt = $db->prepare("insert into match" . $t . " values (:bid, :owner, :title, :cond, :isbn, :want)");
					$stmt->execute([
						':bid' => $y->getBid(),
						':owner' => $y->getOwner(),
						':title' => $y->getTitle(),
						':cond' => $y->getCond(),
						':isbn' => $y->getISBN(),
						':want' => $y->getWant()
					]);
					//remove inelligible books from main table
					$stmt = $db->prepare('delete from book where  want = :bid');
					$stmt->execute([':bid' => $y->getBid()]);

				}
			}
		}
		return $tables;
	} 

	public function saveBook() {
		$db = User::connect();
		$stmt = $db->prepare("insert into book values (NULL, :owner, :title, :cond, :isbn, :want)");
		$stmt->execute([
			':owner' => $this->getOwner(),
			':title' => $this->getTitle(),
			':cond' => $this->getCond(),
			':isbn' => $this->getISBN(),
			':want' => $this->getWant()
		]);
	}

	//purely for demonstration
	public static function makeXbooks($x = 100) {
		$db = User::connect();
		$stmt = $db->prepare("drop table if exists book");
		$stmt->execute();
		$stmt = $db->prepare("CREATE TABLE book (
			'bid'	integer,
			'owner' integer,
			'title' text,
			'cond' text,
			'isbn' text,
			'want' integer,
			PRIMARY KEY('bid'))");
		$stmt->execute();

		for($i=1; $i < $x + 1; $i++) {
			$want = rand(1, $x);
			if($want === $i) {
				$want++;
			}
			$a = new Book(NULL, rand(1,10), "title" . $i, 'good', rand(1000000000, 1999999999), $want);
			$a->saveBook();
		}
	}


}


