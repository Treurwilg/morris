<?php
use PhpSolutions\Authenticate\CheckPassword;
require_once(__DIR__ . '/../../../www/PhpSolutions/Authenticate/CheckPassword.php');
$usernameMinChars = 6;
$errors = [];

if (strlen($username) < $usernameMinChars) {
	$errors[] = "Gebruikersnaam heeft minstens $usernameMinChars karakters.";
}
if (!preg_match('/^[- _\p{L}\d]+$/ui', $username)) {
	$errors[] = 'Alleen alfanumerieke karakters, spaties, streepjes en onderstreepjes 
						zijn toegestaan in gebruikersnaam.';
}
	$checkPwd = new CheckPassword($password, 8);
	$checkPwd->requireMixedCase();
	$checkPwd->requireNumbers(2);
	$checkPwd->requireSymbols();
	if (!$checkPwd->check()) {
		$errors = array_merge($errors, $checkPwd->getErrors());
	} 
	if ($password != $retyped) {
	$errors[] = "Je wachtwoorden komen niet overeen.";	
	}
	if (!$errors) {
		// hash password using default algorithm
		$password = password_hash($password, PASSWORD_DEFAULT);
		require_once './../../private/includes/connection.php';
		$conn = dbConnect('write', 'pdo');
		// prepare SQL statement
		$sql = 'INSERT INTO morris_users (username, blogname, password, email) VALUES (:username, :blogname, :password, :email)';
		$stmt = $conn->prepare($sql);
		// bind parameters and insert details into the database		
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':blogname', $blogname, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 1) {
			$success = htmlentities($username) . ' is geregistreerd. Je kunt nu inloggen.';		
		}	elseif ($stmt->errorCode() == 23000) {
			$errors[] = htmlentities($username) . ' is al in gebruik. Kies een andere naam.';		
		} else {
			$errors[] = $stmt->errorInfo()[2];		
		}
	}
?>