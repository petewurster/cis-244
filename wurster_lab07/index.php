<?php require_once 'head.php'; 

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
		echo '
		<form method="POST">
			<input type="hidden" name="dbExistTest" value="true">
		</form>
		<script>
			let x = $("form");
			x.submit();
		</script>';
		exit;
	//build DB
	}else{
		require_once 'popDB.php';
	}
}
//finally collected!
echo "<b>connected!</b></p>";


//get country names to populate form
$db = new PDO(DB_CONNECT);
$qy = 'select c_name from country order by c_name asc';
$stmt = $db->prepare($qy);
$stmt->execute();

$countries = $stmt->fetchAll(PDO::FETCH_COLUMN);
$country = $_GET['country'] ?? $countries;

//build select based on user choices
$qy = "select museum.m_name, country.c_name from museum, country where museum.c_code = country.cid and (country.c_name='";
foreach($country as $k) {
	$qy .= $k . "' or country.c_name='";
}
$qy = substr_replace($qy, '', -20);
$qy = str_replace("''", "'", $qy) . ") order by c_name asc";
$stmt = $db->prepare($qy);
$stmt->execute();

$table = $stmt->fetchall(PDO::FETCH_ASSOC);

?>


<form method="GET">
	<fieldset>
		<legend>(leave empty to select all)</legend>
	<button type="reset" id="reset">Clear</button>
	<button type="submit">Apply Filter</button><br>
	<?php foreach($countries as $k): ?>
		<label><input checked type="checkbox" name="country[]" value="'<?php echo $k; ?>'"><?php echo $k; ?></label><br>
	<?php endforeach; ?>
	</fieldset>
</form>

<div id="museums">
	<table>
		<?php foreach($table as $row): ?>
		<tr>
			<td><?php echo $row['c_name']; ?></td>
			<td><a href="//en.wikipedia.org/wiki/<?php echo str_replace(' ', '_', $row['m_name']); ?>"><?php echo $row['m_name']; ?></a></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>

</body>
</html>
