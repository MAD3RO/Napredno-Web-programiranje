<?php 

//Ucitaj datoteku
$xml = file_get_contents('LV2.xml');
$xml = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml);
$sxe = simplexml_load_string($xml);
//Iteracija kroz XML
foreach ($sxe->record as $record) 
{ 
	echo '<br><img src = "'.$record->slika.'"><br>';
	echo "<br><div>"."Ime: "."$record->ime<br>";
	echo "<div>"."Prezime: "."$record->prezime";
	echo "<div>"."E-mail: "."$record->email";
	echo "<div>"."Spol: "."$record->spol";
	echo "<div>"."Zivotopis: "."$record->zivotopis"; 
}
?>