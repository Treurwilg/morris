<?php
use PhpClasses\Image\ThumbnailUpload2;
if (isset($_POST['upload'])) {
	require_once('./../PhpClasses/Image/ThumbnailUpload2.php');
	try {
		$loader = new ThumbnailUpload2('/www/morris/images/');	
		$loader->setThumbOptions('/www/morris/images/images_thumb/');
		$loader->upload('image');
		$messages = $loader->getMessages();
	} catch (Exception $e) {
		echo $e->getMessage();	
	}
} 
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta  charset="utf-8">
    <title>Thumbnail Upload</title>
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
<form action="create_thumb_upload.php" method="post" enctype="multipart/form-data">
    <p>
        <label for="image">Upload images to create thumbnails:</label>
        <input type="file" name="image[]" id="image" multiple>
    </p>
    <p>
        <input type="submit" name="upload" id="upload" value="Upload">
    </p>
</form>
</body>
</html>