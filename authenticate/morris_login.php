<?php
// File: morris_login.php
// Bedoeling: demonstreren van het concept 'session'.
// Basis voor: authenticatie in project
// Verder: Start sessie
$error = ' ';
if (isset($_POST['login'])) {
	session_start();
	$username = trim($_POST['username']);
	$password = trim($_POST['pwd']);
	// location to redirect on success
	$redirect = 'https://ict4us.nl/morris/morris_blog.php';
	require_once '/private/morris/includes/authenticate.php';
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Morris MMS Login</title>
</head>

<body>
<p>Nieuw op deze webstek? <a href="https://ict4us.nl/authenticate/verify_email.php" >REGISTREER</a> </p>
<p>Oud en vertrouwd? Log hier in:</p>
<?php
if ($error) {
    echo "<p>$error</p>";
} elseif (isset($_GET['expired'])) { ?>
    <p>Je sessie is verlopen. Log aub weer in.</p>
<?php } ?>
<form method="post" action="morris_login.php">
    <p>
        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" id="username">
    </p>
    <p>
        <label for="pwd">Wachtwoord:</label>
        <input type="password" name="pwd" id="pwd">
    </p>
    <p>
        <input name="login" type="submit" value="Log in">
    </p>
</form>
</body>
</html>
