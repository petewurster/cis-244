<?php
namespace wurster\login;
require_once 'head.php';
require_once 'funcs.php';
require_once 'User.php';
session_start();


if(loginTest()) {
	$user = User::id_login($_SESSION['uid']);
}else{
	header('Location: index.php');
	die;
}

if(isset($_POST['logout'])) {
	$_SESSION = [];
	setcookie(session_name(), '', time() - 86400, '/');
	if (session_status() === PHP_SESSION_ACTIVE) {
    	session_destroy();
	}
	header('Location: index.php');
	die;
}


?>

<form method="POST">
<h1>Welcome home <?php echo san($user->getUsername()); ?>!</h1>

	<ul>
		<?php foreach(scandir('./') as $file): ?>
			<?php echo substr($file, 0, 11);?>
			<?php if(substr($file, 1, 11) === 'wurster_lab'): ?>
			<li><a href="<?php echo $file; ?>"><?php echo $file; ?></a></li>
			<?php endif; ?>

		<?php endforeach; ?>
	</ul>

	<input type="submit" name="logout" value="Logout." id="log">


</form>