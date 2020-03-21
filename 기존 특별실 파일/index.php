<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	//header('Location: '.$uri.'/login.php/');
	echo '<script>document.location.href = \''.$uri.'/login.php\';</script>';
	exit;
?>
Something is wrong with the XAMPP installation :-(
