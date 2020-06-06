<?php 
// contact_us.php: Contactpagina om bericht te sturen, 
include './../../private/morris/includes/title.php'; 
$errors = [];
$missing = [];
$suspect = 1;
// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// email processing script
	$to = 'jan@ict4us.nl';
	$subject = 'Feedback from Morris MMS site';
	// list expected fields
	$expected = ['name', 'email', 'comments'];
	// set required fields
	$required = ['name', 'email', 'comments'];
	// set default values for variables that might not exist

	//create additional headers
	$headers[] = 'From: Morris MMS<jan@ict4us.nl>'; // het emailadres is belangrijk voor de herkenning door de provider
	$headers[] = 'Content-Type: text/plain; charset=utf-8';
	// pattern to locate suspect phrases. Dit zou het begin moeten zijn van processmail.php, maar als ik processmail
	// als include probeer te gebruiken, worden sommige variabelen niet herkend. Ik kan geen verschil vinden met het voorbeeld 
	// van David Powers.
	$pattern = '/[\s\r\n]|Content-Type:|Bcc:|Cc:/i';
	// check the submitted email address
	$suspect = preg_match($pattern, $_POST['email']);
	if (!$suspect) {
		foreach ($_POST as $key => $value) {
			// strip whitespace from $value if not an array
			if (!is_array($value)) {
				$value = trim($value);
			}
			if (!in_array($key, $expected)) {
			// ignore the value, it's not in $expected
				continue;
			}
			if (in_array($key, $required) && empty($value)) {
			// required value is missing
				$missing[] = $key;
				$$key = "";
				continue;
			}
			$$key = $value;
		}
	} 
   // validate the user's email
	if (!$suspect && !empty($email)) {
		$validemail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if ($validemail) {
			$headers[] = "Reply-To: $validemail";
		} else {
			$errors['email'] = true;
		}
	}
	$mailSent = false;
	
	// go ahead only if not suspect, all required fields OK, and no errors
	if(!$suspect && !$missing && !$errors) {
		// initialize the message variable
		$message = '';
		// loop through the $expected array
		foreach($expected as $item) {
			// assign the value of the current item to $val
			if (isset($$item) && !empty($$item)) {
				$val = $$item;
			} else {
				// if it has no value, assign 'Not Selected'
				$val = 'Not Selected';
			}
			// if an array, expand as an comma-separated string
			if (is_array($val)) {
				$val = implode(',', $val);
			}
			// replace underscores in the label with spaces
			$item = str_replace('_', ' ', $item);
			// add label and message to the message body
			$message .= ucfirst($item) . ": $val\r\n\r\n";
		}
		// limit line length to 70 characters
		$message = wordwrap($message, 70);
		// format headers as a single string
		$headers = implode("\r\n", $headers);
		$mailSent = mail($to, $subject, $message, $headers); // het 5de argument blijkt geen rol te spelen.
		if (!$mailSent) {
			$errors['mailfail'] = true;
		}  
	} // einde van wat processmail.php zou moeten zijn
	if ($mailSent) {
		header('Location: https://www.ict4us.nl/morris/thank_you.php');
		exit;	
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Morris MMS<?= $title ?></title>
    <link href="styles/morris.css" rel="stylesheet" type="text/css">
</head>

<body>
<header>
    <h1>Morris MMS</h1>
</header>
<div id="wrapper">
    <?php require '/private/morris/includes/menu.php'; ?>
    <main>
        <h2>Neem contact met ons op</h2>
        <?php if (($_POST && $suspect) || ($_POST && isset($errors['mailfail']))) { 
        echo ' ' . $suspect . ' ' . $errors['mailfail'] . ' ' ?>
        	<p class="warning">Sorry, je mail kon niet worden verzonden.
        	Probeer aub later.</p>
        <?php } elseif ($missing || $errors) { ?> 
        		<p class="warning">Verbeter de aangegeven onderdelen.</p>
        	<?php } ?>
        <p>Een bericht aan de Morrisgroep:</p>
        <form method="post" action="contact_us.php">
            <p>
                <label for="name">Naam:
                <?php if (in_array('name', $missing)) { ?>
                	<span class="warning">Voer je naam in</span>
                <?php } ?>	
                </label>
                <input name="name" id="name" type="text"
                	<?php if ($missing || $errors) {
                	echo 'value="' . htmlentities($name) . '"';
                	} ?>>
            </p>
            <p>
                <label for="email">Email:
					<?php if (in_array('email', $missing)) { ?>
                	<span class="warning">Voer je emailadres in</span>
                <?php } elseif (isset($errors['email'])) { ?>
                	<span class="warning">Ongeldig email adres</span>
                <?php } ?>	                
                </label>
                <input name="email" id="email" type="text"
                	<?php if ($missing || $errors) {
                	echo 'value="' . htmlentities($email) . '"';
                	} ?>>
            </p>
            <p>
                <label for="comments">Bericht:
                <?php if (in_array('comments', $missing)) { ?>
                	<span class="warning">Voer een bericht in</span>
                <?php } ?>	
                </label>
                <textarea name="comments" id="comments"><?php
                	if($missing || $errors) {
                		echo htmlentities($comments);
                	} ?></textarea>
            </p>
            <p>
                <input name="send" type="submit" value="Versturen">
            </p>
        </form>
        <pre>
        	<?php 
        	if ($_POST && $mailSent) {
        		echo "Message body\n\n";
        		echo htmlentities($message) . "\n";
        		echo 'Headers: ' . htmlentities($headers);
        	} 
        	?>
        </pre>
    </main>
    <?php include '/private/morris/includes/footer.php'; ?>
</div>
</body>
</html>
