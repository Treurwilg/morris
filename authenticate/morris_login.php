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
<
<?php
if ($error) {
    echo "<p>$error</p>";
} elseif (isset($_GET['expired'])) { ?>
    <p>Your session has expired. Please log in again.</p>
<?php } ?>
<form method="post" action="morris_login.php">
    <p>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
    </p>
    <p>
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" id="pwd">
    </p>
    <p>
        <input name="login" type="submit" value="Log in">
    </p>
</form>
</body>
</html>
