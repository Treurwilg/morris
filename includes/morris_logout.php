<?php
// File: morris_logout.php
// Bedoeld: om de gebruiker actief te laten uitloggen 
//          en terug te keren naar morris_login.php
// Basis voor: logout functie in project
// included in: morris_blog.php en morris_blog_insert.php 
// geen session_start, want andere include bevat die al. sessie wordt gekild
// Status: operationeel voor Morris project
// run this script only if the logout button has been clicked
if (isset($_POST['logout'])) {
    // empty the $_SESSION array
    $_SESSION = [];
    // invalidate the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-86400, '/');
    }
    // end session and redirect
    session_destroy();

    header('Location: https://ict4us.nl/authenticate/morris_login.php');
    exit;
}
?>
<form method="post">
    <input name="logout" type="submit" value="Log out">
</form>
