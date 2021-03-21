<?php
   	$target_dir = "uploads/";
	$target_price_file = $target_dir . "priceList.xml";
	$target_article_file = $target_dir . "articleList.xml";
	move_uploaded_file($_FILES["userfiles"]["tmp_name"][0], $target_price_file);
	move_uploaded_file($_FILES["userfiles"]["tmp_name"][1], $target_article_file);

	$servername = "localhost";
	$username = "admin";
	$password = "admin123";
	$dbname = "fs81ag2";
	$conn;
	try{
		$conn = new PDO("mysql:dbname=$dbname;host=$servername", $username, $password);
	} catch(PDOException $e) {
		echo 'Fehler: ' . htmlspecialchars($e->getMessage());
	}


	$create_artikel = "CREATE TABLE IF NOT EXISTS artikel (artikelnummer varchar(200) PRIMARY KEY, bezeichnung1 VARCHAR(200), bezeichnung2 VARCHAR(200), vpe DECIMAL(9,4))";
	$create_preise = "CREATE TABLE IF NOT EXISTS preise (artikelnummer MEDIUMINT PRIMARY KEY, gueltig_bis VARCHAR(200), preis DECIMAL(9,2))";
	$delete_artikel = "delete from artikel";
	$delete_preise = "delete from preise";
	$clean_up = array($create_artikel, $create_preise, $delete_artikel, $delete_preise);
	foreach ($clean_up as $statement){
		$conn->exec($statement);
	}
	$articleXML = new DOMDocument();
	$table = '<table id="myTable" class="display" style = "width:100%"><thead><tr><th>Artikelnummer</th><th>Bez. 1</th><th>Bez. 2</th><th>VPE</th></tr></thead><tbody>';
	$insert_articles = "insert into artikel (artikelnummer, bezeichnung1, bezeichnung2, vpe) values (?,?,?,?);";	
	if (file_exists($target_article_file)) {
		$articleXML->load($target_article_file);
		$articles = $articleXML->getElementsByTagName('artikel');
		foreach($articles as $article){
			$children = $article->childNodes;
			$artNr = $children->item(1)->nodeValue;
			$match = $children->item(3)->nodeValue;
			$name = $children->item(5)->nodeValue;
			$vpe = $children->item(7)->nodeValue;
			$tmp_arr = array($artNr, $match, $name, $vpe);
			$prep = $conn->prepare($insert_articles);
			$prep->execute($tmp_arr);
			$table = $table . '<tr>';
			foreach($tmp_arr as $val){
				$table = $table . '<td>'. $val . '</td>';
			}
			$table = $table . '</tr>';

		}
	} else {
		echo "existiert nicht";
	}
	$table = $table . '</tbody></table>';
	echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" type="text/css" /><script src="js/jquery-3.6.0.min.js"></script><script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script></head><body>' . $table . '<script>$(document).ready(function(){ $('. "'#myTable'" . ').DataTable();});</script> </body></html>';
?>	
