<?php
require_once 'head.php';
require_once 'DBeditor.php';
use wurster\museums\DBeditor;

echo "<p><i>checking database connection.... </i>";

//check for DB file & aqcuire as necessary
$pop = $_POST['dbExistTest'] ?? false;
$qy = 'select c_name from country';
try{
	$db = new PDO(DB_CONNECT);
	$stmt = $db->prepare($qy);
	$stmt->execute();
}catch(Error $e){
	//reload page to populate DB
	if($pop === false) {
		echo '<form method="POST"><input type="hidden" name="dbExistTest" value="true"></form>
			<script>$("form").submit();</script>';
		exit;
	//build DB
	}else{
		$edit = new DBeditor();
		$edit->makeDB();
	}
}
//finally connected!
echo "<b>connected!</b></p>";


//get country names to populate form
$db = new PDO(DB_CONNECT);
$qy = 'select c_name from country order by c_name asc';
$stmt = $db->prepare($qy);
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_COLUMN);
//get city names to populate form
$qy = 'select city_name from city order by city_name asc';
$stmt = $db->prepare($qy);
$stmt->execute();
$cities = $stmt->fetchAll(PDO::FETCH_COLUMN);

if(!isset($_GET['visit1'])) {
	$country = $countries;
	$city =  $cities;
}else{
	$country = $_GET['country'] ?? null;
	$city = $_GET['city'] ?? null;
}

//build select based on user choices
$qy = "select museum.m_name, country.c_name, city.city_name from museum, country, city where museum.c_code = country.cid and museum.city_code = city.cid and (";
//filter countries
if($country !== null) {
	$qy .= "country.c_name='";
	foreach($country as $k) {
		$qy .= $k . "' or country.c_name='";
	}
	$qy = substr_replace($qy, '', -20);
}
//filter cities
if($city !== null) {
	if($country !== null) {
		$qy .= "or city.city_name='";
	}else{
		$qy .= "city.city_name='";
	}
	foreach($city as $k) {
		$qy .= $k . "' or city.city_name='";
	}
	$qy = substr_replace($qy, '', -20);
}
//clean up query string
$qy .=  ") order by c_name asc ";
//if no selection, select all
if($country === null && $city === null) {
	$qy = "select museum.m_name, country.c_name, city.city_name from museum, country, city where museum.c_code = country.cid and museum.city_code = city.cid order by c_name asc";
}
$stmt = $db->prepare($qy);
$stmt->execute();
$table = $stmt->fetchall(PDO::FETCH_ASSOC);

?>

<form method="GET">
	<fieldset><legend>(leave empty to select all)</legend>
		<button type="reset" class="reset">Clear</button>
		<button type="submit">Apply Filter</button><br>
		<ul>
		<?php foreach($countries as $k): ?>
			<li><input type="checkbox" name="country[]" value="<?php echo $k; ?>" class="top <?php echo str_replace(' ', '-', $k) ;?>"><span><?php echo $k; ?></span>
			<?php
			//get assoc cities via DB selection to list cities under country
				$qy = 'select city_name from city, country where city.c_code = country.cid and country.c_name = "' . $k . '" order by city_name asc';
				$stmt = $db->prepare($qy);
				$stmt->execute();
				$subchecks = $stmt->fetchall(PDO::FETCH_ASSOC);
				$dblcty = '';
			?>
				<ul>
				<?php foreach($subchecks as $sub): ?>
					<?php  if($dblcty !== $sub['city_name']): ?>
						<li><input type="checkbox" name="city[]" value="<?php echo $sub['city_name']; ?>" class="<?php echo str_replace(' ', '-', $k) ;?>"><?php echo $sub['city_name'] ;?></li>
					<?php endif; ?>
					<?php $dblcty = $sub['city_name']; ?>
				<?php endforeach; ?>
				</ul>
			</li>
		<?php endforeach; ?>
		</ul>
		<button type="reset" class="reset">Clear</button>
		<button type="submit">Apply Filter</button>
	</fieldset>
	
	<!-- controls how a page loads absent a GET value -->
	<input type="hidden" name="visit1" value="false">
</form>

<div id="museums">
	<table>
		<thead>
			<tr>
				<td colspan="3"><h2><a href="./DBadmin.php">go to EDIT view</a></h2></td>
			</tr>
		</thead>
		<?php foreach($table as $row): ?>
		<tr>
			<td><?php echo $row['city_name']; ?></td>
			<td><?php echo $row['c_name']; ?></td>
			<td><a href="//en.wikipedia.org/wiki/<?php echo str_replace(' ', '_', $row['m_name']); ?>"><?php echo $row['m_name']; ?></a></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>

</body>
</html>
