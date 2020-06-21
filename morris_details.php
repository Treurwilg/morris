<?php 
	// Relative path to image directory
	$imageDir = './images/images_web/';
	require_once '../../private/includes/utility_funcs.php';
	require_once '../../private/includes/connection.php';
	require_once '/private/morris/includes/morris_session_timeout.php';
	// connect to the database
	$conn = dbConnect('read', 'pdo');
	// check for article_id in query string
	if (isset($_GET['article_id']) && is_numeric($_GET['article_id'])) {
		$article_id = (int) $_GET['article_id'];	
	} else {
		$article_id = 0;	
	}
	$sql = "SELECT title, article, writer, DATE_FORMAT(created, '%W, %M %D, %Y') AS updated, filename_web, caption
				FROM morris_blog LEFT JOIN morris_images USING (image_id)
				WHERE morris_blog.article_id = $article_id";
	$result = $conn->query($sql);
	$row = $result->fetch();
	if ($row && !empty($row['filename_web'])) {
		$image = $imageDir . basename($row['filename_web']);
		if (file_exists($image) && is_readable($image)) {
			$imageSize = getimagesize($image)[3];		
		}	
	}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta  charset="utf-8">
    <title>Morris MMS Logboek | Detail</title>
    <link href="https://ict4us.nl/morris/styles/morris.css" rel="stylesheet" type="text/css">
</head>

<body>
<header>
    <h1>Morris MMS Logboek | Detail</h1>
</header>
<div id="wrapper">
    <?php require '../../private/morris/includes/menu.php'; ?>
    <main>
        <h2><?php if ($row) {
        		$fullHeading = safe($row['title']) . ' (' . safe($row['writer']) . ')';
				echo $fullHeading;        
        } else {
				echo 'No record found';        
        }
        ?>
        </h2>
        <p><?php if ($row) { echo $row['updated']; } ?></p>
        <?php if (!empty($imageSize)) { ?>
	        <figure>
	            <img src="<?= $image ?>" alt="<?= safe($row['caption']) ?>" <?= $imageSize ?>>
	        </figure>
        <?php } 
        $toegelatenTags = '<p><i><strong><a><h1><h2><h3><h4><li><ul><ol><figure><tbody><table><blockquote><tr><td>';
        if ($row) { echo strip_tags($row['article'], $toegelatenTags) /** convertToParas($row['article'])*/ ; 
        }?>
        <p><a href="
			<?php
			// check that browser supports $_SERVER variables
			if (isset($_SERVER['HTTP_REFERER']) && isset($_SERVER['HTTP_HOST'])) {
				$url = parse_url($_SERVER['HTTP_REFERER']);
				// find if visitor was referred from a different domain
				if ($url['host'] == $_SERVER['HTTP_HOST']) {
					//if same domain, use referring URL
					echo $_SERVER['HTTP_REFERER'];
				}			
			}  else {
				// otherwise, send to main page
				echo 'morris_blog.php';			
			} ?>">Terug naar het logboek</a></p>
			<?php include '/private/morris/includes/morris_logout.php'; ?>
    </main>
    <?php include '../../private/morris/includes/footer.php'; ?>
</div>
</body>
</html>
