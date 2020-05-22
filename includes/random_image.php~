<?php
$images = [
['file' 		=> 'BobineAansluiting_web',
 'caption' 	=> 'Zo moet de bobine aan het MMS'],
['file' 		=> 'deKopEraf_web',
 'caption' 	=> 'Blik op de cilinders en zuigers'],
['file' 		=> 'ietsAnders_web',
 'caption' 	=> 'Een andere motor van de distributiezijde'],
['file' 		=> 'morrisTuut_web',
 'caption' 	=> 'De motorvariabelen volgens TunerStudio'],
['file' 		=> 'nogAnders_web',
 'caption' 	=> 'Nog andere motor vanaf vliegwielzijde'],
['file' 		=> 'opTekening_web',
 'caption' 	=> 'De samenstelling van de Morris motor'],
['file' 		=> 'zijklepper_web',
 'caption' 	=> 'Het enige model zijklepper van Morris'],
['file' 		=> 'landroverblok_web',
 'caption' 	=> 'Het zwaarste stuk helemaal kaal']
];
$i = random_int(0, count($images)-1);
$selectedImage = "./images/images_web/{$images[$i]['file']}.jpg";
$caption = $images[$i]['caption'];
if (file_exists($selectedImage) && is_readable($selectedImage)) {
	$imageSize = getimagesize($selectedImage); 
}