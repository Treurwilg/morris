<?php 
	include '../../private/morris/includes/title.php'; 
	require_once '../../private/includes/connection.php';
	require_once '../../private/includes/utility_funcs.php';
	// require_once '/private/morris/includes/morris_session_timeout.php';
	$imageDir = './images/images_thumb/';
	$conn = dbConnect('read', 'pdo');
	$sql = 'SELECT * 
				FROM morris_blog 	
				LEFT JOIN morris_images 
				ON morris_blog.image_id = morris_images.image_id 
				ORDER BY morris_blog.created DESC ';
	$result = $conn->query($sql);
	$errorInfo = $conn->errorInfo();
	if (isset($errorInfo[2])) {
		$error = $errorInfo[2];	
	}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Morris<?php if(isset($title)) { echo "&mdash;{$title}"; } ?></title>
    <link href="https://ict4us.nl/morris/styles/morris.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <h1>Logboek</h1>
</header>
<div id="wrapper">
    <?php require '../../private/morris/includes/menu.php'; ?>
    <main>
       <?php if (isset($error)) {
			echo "<p>$error</p>";       
       }	else {
       	while ($row = $result->fetch())	{
       		$articleHeading = $row['created'] . ' ' . $row['title'] . ' ' . ' (' . $row['writer'] . ')';
				// echo "<h2>{$row['created']}</h2>";
				$extract = getFirst($row['article'], 2);
				echo '<p>';
				if (!empty($row['filename_thumb'])) {
					$image = $imageDir . basename($row['filename_thumb']);	
					if (file_exists($image) && is_readable($image)) {
						$imageSize = getimagesize($image)[3];	
						if (!empty($imageSize)) { ?>
							<figure class="blog_thumb">
								<img src="<?= $image ?>" alt="<?= safe($row['caption']) ?>" <?= $imageSize ?>>
							</figure>
						<?php }				
					}			
				} 
				echo "<h3>$articleHeading</h3>";
				$toegelatenTags = '<p><i><strong><a><h1><h2><h3><h4><li><ul><ol><figure><tbody><table><blockquote><tr><td>';
				echo  strip_tags($extract[0], $toegelatenTags); // safe($extract[0]);
				if ($extract[1]) {
					echo '<a href="https://ict4us.nl/morris/morris_details.php?article_id=' . $row['article_id'] . '">Meer</a>';				
				}
				echo '<p>---</p>';
				echo '</p>';    	
       	}
       }
      ?>
       // <?php include '/private/morris/includes/morris_logout.php'; ?> 
    </main>
    <?php include '../../private/morris/includes/footer.php'; ?>
</body>
</html>
