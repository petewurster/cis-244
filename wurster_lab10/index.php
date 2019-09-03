<?php
namespace wurster\login;
require_once 'head.php';
require_once 'User.php';
require_once 'funcs.php';
session_start();

if(loginTest()) {
	$user = User::id_login($_SESSION['uid']);
	header('Location: home.php');
}

//validate input here
$userinput = stripPost();
$error = array_pop($userinput);

chkCreds();

?>


<form method="POST">
	<h1>Sessions</h1>
		<label>username <input type="text" name="username" value="<?php echo san(isset($_POST['username']) ? $_POST['username'] : ''); ?>"></label><br>
		<label>password <input type="password" name="password" value="<?php echo san(isset($_POST['password']) ? $_POST['password'] : ''); ?>"></label><br>
	<?php if(isset($user) || isset($_POST['login'])): ?>
		<label>Welcome, NEW USER!!!<br>password <input type="password" name="confirm" id="confirm"><br></label>
	<?php endif; ?>
	
		<input type="submit" name="login" value="Login!" id="log">
</form>




</body>
</html>