<?php
// File: authenticate.php
// Bedoeling: controleren opgegeven wachtwoord
// Included in: morris_login.php
// Verder: geeft sessie naam en tijd; regenereert sessie_id
// Status: operationeel voor Morris project
require_once '/private/includes/connection.php';
$conn = dbConnect('read', 'pdo');
// get username's hashed password from the database
$sql = 'SELECT password, blogname FROM morris_users WHERE username = ?';
// prepare statement
$stmt = $conn->prepare($sql);
// pass the input parameter as single-element array
$stmt->execute([$username]);
$row = $stmt->fetch();
$storedPwd = $row[0];
$blogname = $row[1];
// check	the submitted password against the stored version
if (password_verify($password, $storedPwd)) {
	$_SESSION['authenticated'] = 'Jethro Tull';
	// get the time the session started
	$_SESSION['start'] = time();
	// get name of person to write blog entry
	$_SESSION['blogname'] = $blogname;
	session_regenerate_id();
	header("Location: $redirect");
	exit;
} else {
	// if not verified, prepare error message
	$error = 'Ongeldige gebruikersnaam en/of ongeldig wachtwoord.';
}														
?>