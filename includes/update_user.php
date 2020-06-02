<?php
use classes\CheckPassword; // PhpClasses\Authenticate
require_once __DIR__ . '/../classes/CheckPassword.php'; // PhpClasses/Authenticate/
$usernameMinChars = 2;
$errors = [];
$success = '';
if (strlen($username) < $usernameMinChars) {
	$errors[] = "Gebruikersnaam heeft minstens $usernameMinChars karakters.";
}
if (!preg_match('/^[- _\p{L}\d]+$/ui', $username)) {
	$errors[] = 'Alleen alfanumerieke karakters, spaties, streepjes en onderstreepjes 
						zijn toegestaan in gebruikersnaam.';
}
	if (!$errors) {
		require_once '/private/includes/connection.php';
		$conn = dbConnect('write', 'pdo');
		$sql = 'UPDATE morris_users SET blogname = :blogname WHERE user_id = :user_id';
		$stmt = $conn->prepare($sql);		
		$stmt->bindParam(':blogname', $blogname, PDO::PARAM_STR);
		$stmt->bindParam(':user_id', $lastUserId, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() == 1) {
			$success = htmlentities($blogname) . ' is geregistreerd. Je kunt nu inloggen.';		
		}	elseif ($stmt->errorCode() == 23000) {
			$errors[] = htmlentities($blogname) . ' is al in gebruik. Kies een andere logboeknaam.';		
		} else {
			$errors[] = $stmt->errorInfo()[2];		
		}
	}
?>