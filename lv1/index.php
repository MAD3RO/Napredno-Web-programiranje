<!DOCTYPE html>
<html>
<head>
	<title>Laboratorijska vježba 1</title>
	<?php include("DiplomskiRadovi.php"); ?>
</head>
<body>
	<?php
        $rad = new DiplomskiRadovi();
        for($i = 2; $i < 6; $i++)
        {
            $rad->create($i);
            $rad -> save();
        }

        $dbContent = array();
		$dbContent = $rad->read();
	?>

<h1>Database read</h1>
<table>
      <thead>
        <tr>
          <th>OIB tvrtke</th>
          <th>Link rada</th>
          <th>Naziv rada</th>
          <th>Tekst rada</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while( $value = mysqli_fetch_assoc($dbContent))
          {
            echo
            "<tr>
              <td>{$value['oib_tvrtke']}</td>
              <td>{$value['link_rada']}</td>
              <td>{$value['naziv_rada']}</td>
              <td>{$value['tekst_rada']}</td>
            </tr>";
          }
        ?>
      </tbody>
</table>
</body>
</html>