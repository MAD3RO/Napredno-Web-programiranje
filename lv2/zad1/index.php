<?php

$conn = mysqli_connect('localhost','root','','social_network') or die("Connection was not established."); // Spajanje sa bazom
$tables = array();
$columns = array();
$result = mysqli_query($conn,"SHOW TABLES"); 
while($row = mysqli_fetch_row($result)){ // Dohvacanje svih tablica iz baze
	$tables[] = $row[0]; // Spremanje imena tablica u lokalnu varijablu
}

$return = ''; // Deklariranje polja izlaznog sadrzaja 

foreach($tables as $table){ // Prolaz kroz sve tablice
	$result = mysqli_query($conn,"SELECT * FROM " .$table); // Dohvacanje cijelog sadrzaja pojedine tablice
	$num_fields = mysqli_num_fields($result); // Racunanje broja atributa

	for($i=0; $i<$num_fields; $i++){ 
		while($row = mysqli_fetch_row($result)){ // Prolaz kroz svaku tablicu

			$result2 = mysqli_query($conn,"SHOW COLUMNS FROM " .$table); // Dohvacanje atributa pojedine tablice
			$return .= "INSERT INTO ".$table. " ("; // Pocetak inicijalizacije izlaznog polja
			$iterator = 0; // brojac iteracija while petlje
			while($row2 = mysqli_fetch_row($result2)){ // Prolaz kroz svaki atribut
				$iterator++;
				// Formatiranje naziva atributa
				$row2[0] = addslashes($row2[0]);
				if(isset($row2[0])){ $return .= "'".$row2[0]."'";}
				else{ $return .= "'";}
				if($iterator<$num_fields){ $return .= ',';}		
			}

			$return .= ")\r\n\r\n";
			// Prikaz i formatiranje vrijednosti pojedine tablice
			$return .= "VALUES(";
			for($j=0;$j<$num_fields;$j++){
				$row[$j] = addslashes($row[$j]);
				if(isset($row[$j])){ $return .= "'".$row[$j]."'";}
				else{ $return .= "'";}
				if($j<$num_fields-1){ $return .= ',';}
			}
			$return .= ");\r\n\r\n";
		}
	}
	$return .= "\n\n\n";
}
// Kreiranje i spremanje tekstualnog dokumenta sa izlaznim sadrzajem
$handle = fopen("backup.txt","w+");
fwrite($handle,$return);
fclose($handle);
echo "Successfully backed up";

?>