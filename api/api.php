<?php

require_once "config.php";

$gelentur = $_GET['tur'];
$gelenanahtar = $_GET['anahtar'];
$gelenveri = $_GET['veri'];

if($gelenanahtar == 'e09836e1adf09a174cf1a9fdb07156aa'){
	if($gelentur != ''){
	if($gelentur == 'sicaklik'){
		if($gelenveri != ''){
		$sicaklikveri=$pdo->prepare("UPDATE telemetri SET sicaklik = :sicaklik"); 
		$sicaklikveri->bindValue(':sicaklik', $gelenveri, PDO::PARAM_STR);
		
		$sicakliksonuc=$sicaklikveri->execute();
		
		if($sicakliksonuc){
			die("1");
		}else{
			die("0");
		}
		}else{
			die("Boş sıcaklık gönderilemez");
		}
	}else if($gelentur == 'nem'){
		if($gelenveri != ''){
		$nemveri=$pdo->prepare("UPDATE telemetri SET nem = :nem"); 
		$nemveri->bindValue(':nem', $gelenveri, PDO::PARAM_STR);
		
		$nemsonuc=$nemveri->execute();
		
		if($nemsonuc){
			die("1");
		}else{
			die("0");
		}
		}else{
		die("Boş nem gönderilemez");
		}
	}
	}else{
	die("Tür belirtilmedi");
	}
}else{
die("Api anahtarı yanlış!");
}


?>