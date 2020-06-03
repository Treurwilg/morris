<?php
use classes\CheckPassword; // PhpClasses\Authenticate
require_once __DIR__ . '/../classes/CheckPassword.php'; // PhpClasses/Authenticate/
$usernameMinChars = 6;
$errors = [];
$success = '';
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
		require_once '/private/includes/connection.php';
		$conn = dbConnect('write', 'pdo');
		// prepare SQL statement
		$sql = 'INSERT INTO morris_users (username, password) VALUES (:username, :password)';
		$stmt = $conn->prepare($sql);
		// bind parameters and insert details into the database		
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();
		$lastUserId = $conn->lastInsertId();
		if ($stmt->rowCount() == 1) {
			$success = htmlentities($username) . ' is geregistreerd. Voer logboeknaam in met maximaal 10 karakters.';		
		}	elseif ($stmt->errorCode() == 23000) {
			$errors[] = htmlentities($username) . ' is al in gebruik. Kies een andere naam.';		
		} else {
			$errors[] = $stmt->errorInfo()[2];		
		}
	}
?>