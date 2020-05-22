<?php
// File: morris_menu.php
// Bedoeling: demonstreren van concept 'session'. 
// Uiteindelijk: voorbeeld van het includen van logout en timeout.
require_once '/private/morris/includes/morris_session_timeout.php'; 
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Secret menu</title>
</head>

<body>
<h1>Restricted area</h1>
<p><a href="morris_secretpage.php">Go to another secret page</a> </p>
<?php include '/private/morris/includes/morris_logout.php'; ?>
</body>
</html>
