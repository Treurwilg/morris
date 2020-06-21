<?php
	use PhpClasses\File\Upload;
	use PhpClasses\Image\Thumbnail2;
	require_once '/private/includes/connection.php';
	require_once '/private/includes/utility_funcs.php';
	require_once '/private/morris/includes/morris_session_timeout.php';
	include '/private/morris/includes/title.php';
	// create database connection
	$conn = dbConnect('write', 'pdo');
	if (isset($_POST['insert'])) {
		// initialize flag
		$OK = false;
		// if a file has been uploaded, process it
		if (isset($_POST['upload_new']) && $_FILES['image']['error'] == 0) {
			$imageOK = false;
			require_once '../PhpClasses/File/Upload.php';
			$loader = new Upload('images/'); // hier werd eerst nog een niveau hoger gegaan
			$loader->upload('image'); // hier zit verschil tussen de mysqli en pdo?
			$names = $loader->getFilenames();
			// $names will be an empty array if the upload failed
			if ($names) {
				// use named placeholders
				$sql = 'INSERT INTO morris_images (filename, caption) VALUES (:filename, :caption)';
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':filename', $names[0], PDO::PARAM_STR);
				$stmt->bindParam(':caption', $_POST['caption'], PDO::PARAM_STR);
				$stmt->execute();
				// use rowCount to get the number of affected rows
				$imageOK = $stmt->rowCount();
			}
			// get the image's primary key or find out what went wrong
			if ($imageOK) {
				// lastInsertId() must be called on the PDO connection object	
				$image_id = $conn->lastInsertId();
				//$stmt->closeCursor();
				// if correct this far create web en duim versie van de geuploade foto, zet de namen in 
				// de morris_images tabel en de web en duim versie in de mappen images/images_web en images/images_thumb
				require '../PhpClasses/Image/Thumbnail2.php';
				$webpix = new Thumbnail2('./images/' . $names[0], '/www/morris/images/images_web/', 400, '_web');	
				$duimpix = new Thumbnail2('./images/' . $names[0], '/www/morris/images/images_thumb/', 100, '_duim');	
				$filename_web = $webpix->create();
				$filename_thumb = $duimpix->create();							
				$sql = 'UPDATE morris_images SET filename_web = ?, filename_thumb = ? WHERE image_id = ?';
				$stmt2 = $conn->prepare($sql);
				$done = $stmt2->execute([$filename_web, $filename_thumb, $image_id]);	
			} else {
				$imageError = implode(' ' , $loader->getMessages());
			}
		} elseif (isset($_POST['image_id']) && !empty($_POST['image_id'])) {
			// get the primary key of a previously uploaded image
			$image_id = $_POST['image_id'];	
		}
		// insert blog details only if there hasn't been an image upload error
		if (!isset($imageError)) {
			// create SQL
			// $writer = $_SESSION['blogname'];
			$sql = 'INSERT INTO morris_blog (image_id, title, article, writer) VALUES(:image_id, :title, :article, :writer)'; 			
			//prepare the statement
			$stmt = $conn->prepare($sql);
			// bind the parameters
			// if $image_id exists, use it
			if (isset($image_id)){
				$stmt->bindParam(':image_id', $image_id, PDO::PARAM_INT);		
			} else {
				// set image_id to NULL
				$stmt->bindValue(':image_id', NULL, PDO::PARAM_NULL);			
			}
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':article', $_POST['article'], PDO::PARAM_STR);
			$stmt->bindParam(':writer', $_SESSION['blogname'], PDO::PARAM_STR);
			// execute and get number of affected rows
			$stmt->execute();
			$OK = $stmt->rowCount();
		}
		// if the blog entry was inserted successfully, check for categories
		if ($OK && isset($_POST['category'])) {
			// get the article's primary key
			$article_id = $conn->lastInsertId();
			foreach ($_POST['category'] as $cat_id) {
				if (is_numeric($cat_id)) {
					$values[] = "(article_id, " . (int) $cat_id . ')';				
				}			
			}	
			if ($values) {
				$sql = 'INSERT INTO morris_article2cat (article_id, cat_id) VALUES ' . implode(',', $values);
				// execute the query and get error message if it fails
				if (!$conn->exec($sql)) {
					$catError = $conn->error;				
				}			
			}
		}
		// redirect if successful or display error
		if($OK && !isset($imageError) && !isset($categoryError)) {
			header('Location: https://ict4us.nl/morris/morris_blog.php');
			exit;		
		}	else {
			$error = '';
			if (isset($stmt)) {
				$error .= $stmt->errorInfo()[2];			
			}	else {
				$error .= $conn->errorInfo()[2];			
			}
			if (isset($imageError)) {
				$error .= ' ' . $imageError;			
			}
			if (isset($catError))
			 {
				$error .= ' ' . $catError;			
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Aanvullen</title>
<link href="./styles/morris.css" rel="stylesheet" type="text/css">
<script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
</head>
<body>
<header>
	<?php 
	if (isset($_SESSION['blogname'])) {
		echo "<p>Hallo $_SESSION[blogname], maak er wat moois van.</p>";
	}
	if (isset($error)) {
		echo "<p>Error: $error</p>";	
	} ?>
</header>
<div id="wrapper">
	<?php 
	    $file = '/private/morris/includes/menu.php';
	    if (file_exists($file) && is_readable($file)) {
	    	require $file;
	    } else {
	    	throw new Exception("$file can't be found"); 
	    } 
	?>
	<main>
		<h1>Logboek aanvullen</h1>
		<form method="post" action="morris_blog_insert.php" enctype="multipart/form-data">
			<p>
				<label for="title">Titel:</label>
				<input name="title" type="text" id="title" value="<?php if (isset($error)) {echo safe($_POST['title']); } ?>">		
			</p>
			<p>
				<label for="article">Artikel:</label>
				<textarea name="article" id="article"><?php if (isset($error)) { echo safe($_POST['article']); } ?></textarea>			
			</p>	
			<p>
				<label for="category">Categorieën:</label>
				<select name="category[]" size="5" multiple id="category">
					<?php
					// get categories
					$getCats = 'SELECT cat_id, category FROM morris_categories ORDER BY category';
					foreach ($conn->query($getCats) as $row) { ?>
						<option value="<?= $row['cat_id'] ?>" <?php
						if (isset($_POST['category']) && in_array($row['cat_id'], $_POST['category'])) {
								echo 'selected';
						} ?>><?= safe($row['category']) ?></option>	
					<?php } ?>
				</select>		
			</p>
			<p class="optional">
				<label for="image">Kies foto:</label>
				<input type="file" name="image" id="image">			
			</p>
			<p class="optional">
				<label for="caption">Onderschrift foto:</label>	
				<input name="caption" type="text" id="caption">		
			</p>
			<p>
				<input type="submit" name="insert" value="Vul logboek aan met dit artikel">		
			</p>
			<?php include '/private/morris/includes/morris_logout.php'; ?>
		</form>
	</main>
	<?php include '/private/morris/includes/footer.php'; ?>
</div>
<script>
    ClassicEditor
        .create( document.querySelector( '#article' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
</body>
</html>