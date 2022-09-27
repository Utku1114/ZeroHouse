<?php
require_once "config.php";
/* Block durumunda siliniz. */
        require('WAF.php');
	$xWAF = new xWAF();

	$xWAF->useCloudflare();

	$xWAF->useBlazingfast();

	$xWAF->customIPHeader('IP-Header');

	$xWAF->antiCookieSteal('username'); 


	$xWAF->checkGET();
	$xWAF->checkPOST();
        $xWAF->checkCOOKIE();

								$sql=$pdo->prepare("select * from roleler");
$sql->execute();
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
  $suanki = $row['durum'];
	
	$array = array('role' => $suanki);
	echo json_encode($array);
}
?>
