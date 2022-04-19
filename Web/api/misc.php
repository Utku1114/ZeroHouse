<?php
require_once "config.php";
								$sql=$pdo->prepare("select * from roleler");
$sql->execute();
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
  $suanki = $row['durum'];
	
	$array = array('role' => $suanki);
	echo json_encode($array);
}
?>