<?php
echo '<!DOCTYPE html><html><body><h1>test</h1><h3>
<form action="upload.php" method="post" enctype="multipart/form-data">
	  Wählen Sie hier die XML-Datei zum Upload aus:<br>
		<inline><h2>Preisliste:</h2><input type="file" name="userfiles[]" /></inline><br>
		<inline><h2>Artikelliste:</h2><input type="file" name="userfiles[]" /></inline><br><br>
		<input type="submit" value="Hochladen" name="sumit"><br><br>
	      </form>';
$servername = "localhost";
$username = "admin";
$password = "admin123";
$dbname = "fs81ag2";

// Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
   }

   $sql = "SELECT gueltig_bis,artnr,preis FROM preise";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
     // output data of each row
       while($row = $result->fetch_assoc()) {
           echo "gueltig_bis: " . $row["gueltig_bis"]. " - Art.Nr.: " . $row["artnr"]. " " . $row["preis"]. "<br>";
             }
             } else {
               echo "0 results";
               }
               $conn->close();
echo "</h3></html></body>";
?>
