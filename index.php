	<?php ob_start();
	try {
	include './../../private/morris/includes/title.php'; 
	include './../../private/morris/includes/random_image.php' ?>
	<!DOCTYPE HTML>
	<html>
	<head>
	    <meta charset="utf-8">
	    <title>Morris <?= $title ?></title>
	    <link href="styles/morris.css" rel="stylesheet" type="text/css">
	    <?php if (isset($imageSize)) { ?>
       <style>
       figcaption {
 				width: <?= $imageSize[0] ?>px;      
       }    
       </style>
       <?php } ?>
 	</head>
	
	<body>
	<header>
	    <h1>Morris MotorManagementSysteem</h1>
	</header>
	<div id="wrapper">
	    <?php 
	    	$file = './../../private/morris/includes/menu.php';
	    	if (file_exists($file) && is_readable($file)) {
	    		require $file;
	    	} else {
	    		throw new Exception("$file can't be found"); 
	      } 
	      ?>
	    <main>
	        <h2>Aan de slag met Morris motor en MMS</h2>
	        <p>In 2019 is bij het Motorenmuseum in Appeltern, onderdeel van Museum Stoomgemaal de Tuut, onder een aantal vrijwilligers het idee geboren een motor uit te rusten met sensoren. Het doel was de interne processen van de motor beter te kunnen begrijpen. En natuurlijk ook om de bezoekers meer inzicht te kunnen geven in de interne processen van de motor.</p>
				<?php if (isset($imageSize)) { ?>	        
	        <figure>
	            <img src="<?= $selectedImage ?>" alt="Random image" class="picBorder" <?= $imageSize[3] ?>>
	            <figcaption><?= $caption ?></figcaption>
	        </figure>
	        <?php } ?>
	        <p>
	         </p>
	        <p>Er werd contact gezocht met een gepensioneerd leraar Motorvoertuigtechniek, die nu cursussen op het gebied van Motor Management Systemen geeft. Dat geeft een mooie start voor het project. Bovendien wist de leraar ons aan een adres te helpen waar een oude oude Morris-motor beschikbaar was, en wel bij een lid van de Nederlandsche Morris-vereniging</p>
	        <p>Begin 2020 begonnen we met een cursus voor 5 man bij betrokken leraar. Ziedaar het materiaal voor de start: een aantal enthousiaste vrijwilligers van het Motorenmuseum, een even enthousiaste leraar Motor Management Systemen en een motor uit de jaren 1960 (of zoiets).</p>
	        <p>Om  in het coronatijdperk de voortgang toch in beeld te brengen en de betrokkenheid hoog te houden, leek het goed idee deze website in het leven te roepen. Het belangrijkste onderdeel is het logboek, waarin de betrokkenen hun ervaringen, ideeën en materiaal kunnen weergeven. Toegang tot de bijbehorende opmaakpagina is vooralsnog voorbehouden aan de leden van de morrisgroep. </p>
	        <p>Verder zijn er een fotogalerij en een contactpagina. Via deze laatste kan eenieder ons benaderen met vragen of ideeën. Na voldoende voortgang zal een nieuwsbrief worden toegevoegd.</p>
	    </main>
	    <?php require './../../private/morris/includes/footer.php'; ?>
	</div>
	</body>
	</html>
	<?php } catch (Exception $e) {
		ob_end_clean();
		header('Location: https://www.ict4us.nl/morris/error.php'); 
	} 
	ob_end_flush();
	?>