<?php

session_start();

$key = md5('nwp_lv2 kljuc');	
$m = mcrypt_module_open('rijndael-256', '', 'cbc', '');	
$iv = base64_decode($_SESSION['iv']);
mcrypt_generic_init($m, $key, $iv);

if($_GET['file']){
	
	$file_url = $_GET['file'];
	$file = file_get_contents($file_url);
	$file = mdecrypt_generic($m, base64_decode($file));
	$base = basename($file_url);
	file_put_contents($base, $file);
	
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . $base . "\""); 
	readfile($base);
	unlink($base);	
}

mcrypt_generic_deinit($m);
mcrypt_module_close($m);

?>