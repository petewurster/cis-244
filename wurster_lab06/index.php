<?php require 'head.php' ?>

<body>

<form action="demo.php" method="POST">
	<fieldset>
		<input type="text" name="fname" placeholder="first name">
		<input type="text" name="lname" placeholder="last name"><br>
	</fieldset>
	<fieldset>
		<input type="email" name="email" placeholder="email">
		<input type="tel" name="phone" placeholder="telephone"><br>
		<input type="text" name="org" placeholder="organization">
		<input type="text" name="title" placeholder="position/title"><br>
	</fieldset>
	<fieldset>
		<input type="text" name="street" id="street" placeholder="street address"><br>
		<input type="text" name="city" placeholder="city">
		<input list="statelist" name="state" placeholder="state">
<!-- I  grabbed this datalist from https://www.freeformatter.com/usa-state-list-html-select.html, not quite sure how to properly cite this type of code usage but i do not intend to take credit for typing all this crap out-->
			<datalist id="statelist">
				<option value="AL">
				<option value="AK">
				<option value="AR">
				<option value="AZ">
				<option value="CA">
				<option value="CO">
				<option value="CT">
				<option value="DC">
				<option value="DE">
				<option value="FL">
				<option value="GA">
				<option value="HI">
				<option value="IA">
				<option value="ID">
				<option value="IL">
				<option value="IN">
				<option value="KS">
				<option value="KY">
				<option value="LA">
				<option value="MA">
				<option value="MD">
				<option value="ME">
				<option value="MI">
				<option value="MN">
				<option value="MO">
				<option value="MS">
				<option value="MT">
				<option value="NC">
				<option value="NE">
				<option value="NH">
				<option value="NJ">
				<option value="NM">		
				<option value="NV">
				<option value="NY">
				<option value="ND">
				<option value="OH">
				<option value="OK">
				<option value="OR">
				<option value="PA">
				<option value="RI">
				<option value="SC">
				<option value="SD">
				<option value="TN">
				<option value="TX">
				<option value="UT">
				<option value="VT">
				<option value="VA">
				<option value="WA">
				<option value="WI">
				<option value="WV">
				<option value="WY">
			</datalist>
		<input type="text" name="zip" placeholder="zip code">
	</fieldset>
	
	<input type="submit" id="submit" value="Create card!">
</form>

</body>
</html>