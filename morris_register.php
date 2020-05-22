<?php
if (isset($_POST['register'])) {
	$username = trim($_POST['username']);
	$blogname = trim($_POST['blogname']);
	$email = trim($_POST['email']);
	$password = trim($_POST['pwd']);
	$retyped = trim($_POST['conf_pwd']);
	require './../../private/morris/includes/register_user.php';
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
<form action="morris_register.php" method="post">
    <p>
        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" id="username">Waarmee je na registratie inlogt(>6 karakters).
    </p>
    <p>
        <label for="blogname">Logboeknaam:</label>
        <input type="text" name="blogname" id="blogname">De naam die anderen zien bij jouw logboekartikelen (<10 karakters).
    </p>
    <p>
			<label for="email">Emailadres:</label>
			<input type="text" name="email" id="email">Waarmee je mag registreren.  
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
