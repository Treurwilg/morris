<?php
use PhpClasses\Image\Thumbnail2;
if (isset($_POST['create'])) {
	require_once('../PhpClasses/Image/Thumbnail2.php');
	try {
		$pix = $_POST['pix'];
		$thumb = new Thumbnail2($pix, '/www/morris/images/images_web', 400, '_web');
		$thumb->test();	
	} catch (Throwable $t) {
		echo $t->getMessage();	
	}
	$thumb->create();
	$messages = $thumb->getMessages();
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Morris Upload</title>
	<link href="styles/morris.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
if (!empty($messages)) {
	echo '<ul>';
	foreach ($messages as $message) {
		echo "<li>$message</li>";	
	}
	echo '</ul>';
}
?>
<form method="post" action="create_thumb.php">
	<p>
		<select name="pix" id="pix">
			<option value="">Kies een foto</option>
			<?php
			$files = new FilesystemIterator('./images');
			$images = new RegexIterator($files, '/\.(?:jpg|png|gif|webp)$/i');
			foreach ($images as $image) { ?>
				<option value="<?= $image->getRealPath() ?>"><?= $image->getFilename() ?></option>	
			<?php } ?>	
		</select>
	</p>
	<p>
		<input type="submit" name="create" value="Maak webversie">	
	</p>
</form>
</body>
</html>
