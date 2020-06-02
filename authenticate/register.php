<?php
session_start();
$redirect = 'https://ict4us.nl/authenticate/morris_login.php';
if (!isset($_SESSION['status'])) {
	header("Location: $redirect");
	exit;	
} else {
	if (isset($_POST['register'])) {
		$username = trim($_POST['username']);
		$password = trim($_POST['pwd']);
		$retyped = trim($_POST['conf_pwd']);
		require '/private/morris/includes/register_user.php';
		if($success) {
			$_SESSION['lastUserId'] = $lastUserId;
			$_SESSION['status'] = 'go';
			$_SESSION['success'] = 'Voer logboeknaam in met maximaal 10 karakters';
			$redirect = 'https://ict4us.nl/authenticate/register_blogname.php';
			header("Location: $redirect");
			exit;
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
<h1>Als gebruiker registreren: naam en wachtwoord</h1>
<?php
	if (isset($success)) {
		echo "<p>$success</p>";
	} elseif (isset($errors) && !empty($errors)) {
		echo '<ul>';
		foreach ($errors as $error) {
			echo "<li>$error</li>";	
		}
		echo '</ul>';
	}
?>
<form action="register.php" method="post">
    <p>
        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" id="username">Waarmee je na registratie inlogt(>6 karakters).
    </p>
    <p>
        <label for="pwd">Wachtwoord:</label>
        <input type="password" name="pwd" id="pwd">Waarmee je na registratie inlogt.
    </p>
    <p>
        <label for="conf_pwd">Wachtwoord nogmaals:</label>
        <input type="password" name="conf_pwd" id="conf_pwd">Om op zeker te spelen.
    </p>
    <p>
        <input type="submit" name="register" value="Registreer">
    </p>
</form>
</body>
</html>
