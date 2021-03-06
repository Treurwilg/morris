<?php
session_start();
ob_start();
$redirect = 'https://ict4us.nl/authenticate/morris_login.php';
if (!isset($_SESSION['status'])) {
	header("Location: $redirect");
	exit;	
}
$blogname = '';
$errors = [];
if (isset($_POST['registreer'])) {
	$blogname = trim($_POST['blogname']);
	if (!preg_match('/^[- _\p{L}\d]+$/ui', $blogname)) {
		$errors[] = 'Alleen alfanumerieke karakters, spaties, streepjes en onderstreepjes 
						zijn toegestaan in gebruikersnaam.';
	}
	$redirect = 'https://ict4us.nl/authenticate/morris_login.php';
	if (!$errors) {
		$lastUserId = $_SESSION['lastUserId'];
		require '/private/morris/includes/update_user.php';
		if($success) {
			header("Location: $redirect");
			unset($_SESSION['status']);
			unset($_SESSION['lastUserId']);
			unset($_SESSION['success']);
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-86400, '/');
			}
			session_destroy();
			ob_end_flush();
		} 
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Registreren</title>
    <style>
        label {
            display:inline-block;
            width:125px;
            text-align:right;
            padding-right:2px;
        }
        input[type="submit"] {
            margin-left:135px;
        }
    </style>
</head>
<body>
<h1>Als gebruiker registreren</h1>
<?php
	if (isset($errors) && !empty($errors)) {
		echo '<ul>';
		foreach ($errors as $error) {
			echo "<li>$error</li>";	
		}
		echo '</ul>';
	}
?>
<form action="register_blogname.php" method="post">
    <p>
        <label for="blogname">Logboeknaam:</label>
        <input type="text" name="blogname" id="blogname">Voor bij jouw logboekartikelen (max 10 karakters).
    </p>
    <p>
        <input type="submit" name="registreer" value="Registreer">
    </p>
</form>
</body>
</html>
