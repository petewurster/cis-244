<?php
require_once 'head.php';
require_once 'DBeditor.php';
use wurster\museums\DBeditor;

$db = new PDO(DB_CONNECT);

if (isset($_POST['edit'])) {
	$edit = new DBeditor();
	$x = explode('++', $_POST['edit']);
	$edit->modRow((int)$x[0], $x[1]);
}

if (isset($_POST['delete'])) {
	$del = new DBeditor();
	$del->delRow($_POST['delete']);
}

if (isset($_POST['add'])) {
	$add = new DBeditor();
	$add->addRow($_POST['addcity'], $_POST['addcountry'], $_POST['addmuseum']);
}

$qy = "select * from museum, country, city where museum.c_code = country.cid and museum.city_code = city.cid order by c_name asc";
$stmt = $db->prepare($qy);
$stmt->execute();
$table = $stmt->fetchall(PDO::FETCH_ASSOC);

?>

<div id="museums" class="admin">
	<form method="POST">
	<table>
		<thead>
			<tr>
				<td colspan="4"><h2><a href="./index.php">go to SEARCH view</a></h2></td>
			</tr>
		</thead>
		<tr>
			<td><input type="text" name="addcity" placeholder="city" required></td>
			<td><input type="text" name="addcountry" placeholder="country" required></td>
			<td><input type="text" name="addmuseum" placeholder="museum" required></td>
			<td><input type="submit" name="add" value="add entry"></td>		
		</tr>
	</table>
	</form>

	<form method="POST">
	<table>
		<?php foreach($table as $row): ?>
		<tr>
			<td><?php echo $row['city_name']; ?></td>
			<td><?php echo $row['c_name']; ?></td>
			<td><a href="//en.wikipedia.org/wiki/<?php echo str_replace(' ', '_', $row['m_name']); ?>"><?php echo $row['m_name']; ?></a></td>
			<td><input type="submit" name="edit" value="edit" class="e e_<?php echo $row['mid'];?>"></td>
			<td><input type="submit" name="delete" value="delete" class="d d_<?php echo $row['mid'];?>"></td>
		</tr>
		<?php endforeach; ?>
	</table>
	</form>
	
	<form method="POST">
	<table>
		<tr>
			<td><input type="text" name="addcity" required></td>
			<td><input type="text" name="addcountry" required></td>			
			<td><input type="text" name="addmuseum" required></td>
			<td><input type="submit" name="add" value="add museum"></td>		
		</tr>
	</table>
	</form>
</div>

</body>
</html>

