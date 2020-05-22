<?php 
$title = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$title = str_replace('_', ' ', $title);
if ($title == 'index') {
	$title = 'startpagina';
}
if ($title == 'morris_blog') {
	$title = 'logboek';
}
$title = ucwords($title);