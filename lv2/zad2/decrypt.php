<?php

session_start();

if (isset($_SESSION['podaci'], $_SESSION['iv'])) 
{
	$key = md5('nwp_lv2 kljuc');
	$m = mcrypt_module_open('rijndael-256', '', 'cbc', '');	
	$iv = base64_decode($_SESSION['iv']);
	mcrypt_generic_init($m, $key, $iv);

	$files = glob('upload/*.{jpg,png,pdf}', GLOB_BRACE);
	foreach($files as $key => $file) 
	{
		echo '<a href="download.php?file='.$file.'">Download '.$file.'</a><br>';
	}

	$data = mdecrypt_generic($m, base64_decode($_SESSION['podaci']));
	mcrypt_generic_deinit($m);
	mcrypt_module_close($m);
	echo "<p>Dekriptirani podaci su " . trim($data) . ".</p>";
}
else
{
	echo "<p>Nema podataka.</p>";
}
?>