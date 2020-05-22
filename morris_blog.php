<?php 
	include '../../private/morris/includes/title.php'; 
	require_once '../../private/includes/connection.php';
	require_once '../../private/includes/utility_funcs.php';
	// create database connection
	$conn = dbConnect('read', 'pdo');
	$sql = 'SELECT * FROM morris_blog ORDER BY created DESC';
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
    <title>Morris MMS<?php if(isset($title)) { echo "&mdash;{$title}"; } ?></title>
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
       		$articleHeading = $row['created'] . " " . $row['title'];
				echo "<h2>$articleHeading</h2>";
				// echo "<h2>{$row['created']}</h2>";
				$extract = getFirst($row['article'], 2);
				echo '<p>' . safe($extract[0]);
				if ($extract[1]) {
					echo '<a href="https://ict4us.nl/morris/morris_details.php?article_id=' . $row['article_id'] . '">Meer</a>';				
				}
				echo '</p>';    	
       	}
       }
      ?>
    </main>
    <?php include '../../private/morris/includes/footer.php'; ?>
</div>
</body>
</html>
