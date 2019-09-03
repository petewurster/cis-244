<?php require 'head.php'; ?>

<form action="index.php" method="get" accept-charset="utf-8">
	<label>rows<input type="number" name="rows" value = <?php echo $_GET['rows'] ?? 9; ?> ></label>
	<label>cols<input type="number" name="cols" value = <?php echo $_GET['cols'] ?? 9; ?> ></label>
	<input type="submit" name="submit" value="Submit!" id="submit">
</form>

<?php

$rowLimit = $_GET['rows'] ?? 9;
$colLimit = $_GET['cols'] ?? 9;

	echo "\n" . '<table>' . "\n";
	//loop by row
	for($row = 0; $row < $rowLimit + 1; $row++) {
		echo '<tr>';
		//loop by column
		for($col = 0; $col < $colLimit + 1; $col++) {
			echo '<td class = "';
			//test for and appropriately label axes
			echo ($row === 0) ? 'axis">' . $col
				: (($col === 0) ? 'axis">' . $row
					//if not an axis: display product of axes & color table based on odds/evens 
					: ((($row * $col) % 2 === 0) ? 'even'
						: 'odd'	) . '">' . $row * $col) .'</td>';
		}
		echo '</tr>' . "\n";
	}
	echo '</table>';

?>

</body>
</html>