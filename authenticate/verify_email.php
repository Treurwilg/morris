<?php 
// File: verify_email.php
// Bedoeling: toelating tot registratie op basis
// van tijdelijk in database aanwezig emailadres van 
// kandidaatleden.
include '/private/morris/includes/title.php';
include '/private/includes/utility_funcs.php';
$success = '';
$error = [];
$affected = 0;
$email_id = 999;
$email = '';
if (isset($_POST['verify']) && isset($_POST['email'])) {
	session_start();
	$email = htmlentities($_POST['email']);
	require_once '/private/includes/connection.php';
	$conn = dbConnect('read', 'pdo');
	$sql = 'SELECT * FROM temp_emails';
	$result = $conn->query($sql);
	$error[] = $conn->errorInfo()[2];
	while ($row = $result->fetch()) {
		if (safe($row['email'] == $email))	{
			$email_id = $row['email_id'];
			$success = 'Je kunt nu registreren';
			$_SESSION['success'] = $success;
		} 
	}
	if (!$success) {
		$error[] = $email . ' komt niet in aanmerking voor registratie';
	}
	$conn = null;
	if ($success) {
		$conn = dbConnect('write', 'pdo');
		if (isset($email_id)) {	
			$sql = 'DELETE FROM temp_emails WHERE email_id = :email_id';
			$stmt = $conn->prepare($sql);
			$stmt->execute([':email_id' => $email_id]);
			$error[] = $stmt->errorInfo()[2];
			$affected = $stmt->rowCount();
		} 
		if ($affected == 1) {
				$_SESSION['status'] = 'Email is oke, registreer nu naam en wachtwoord';
				$redirect = 'https://ict4us.nl/authenticate/register.php';
				header("Location: $redirect");
				exit;	
		} else {
			$error[] = 'Er is iets misgegaan. Contact de beheerder';	
			unset($_SESSION['status']);
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-86400, '/');
			}
			session_destroy();
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
<h1>Als gebruiker registreren: controle email</h1>
<?php 
 // if ($email) {
	// echo $email . ' ' . $email_id . ' ' . $success . ' ' . $affected;
// }
if (isset($error)) {
	echo implode(" ",$error);
} 
?>
<form action="verify_email.php" method="post">
    <p>
			<label for="email">Emailadres:</label>
			<input type="text" name="email" id="email">Waarmee je mag registreren.  
    </p>

    <p>
        <input type="submit" name="verify" value="Controleer">
    </p>
</form>
</body>
</html>