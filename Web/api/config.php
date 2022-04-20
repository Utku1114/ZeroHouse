<?php

$wafSystemActive = 0;
$blockMessage = "SQL injection detected and <font style='color:red;'>BLOCKED</font>!<br>ZeroHouse Protection";

if($wafSystemActive == 1)
{	
	if(preg_match("/([%\$#\*]+)/", $_SERVER["QUERY_STRING"])) //Firewall Step 1
		die($blockMessage);
	
	$blockedQuerys = array("sql", "%", "[", "*", "UPDATE", "INSERT", "%27", "SELECT", "WHERE", "DELETE", "update", "insert", "select", "where", "delete", "%A2", "rank=", "FROM", "from", "$", "^", "#", "+", "<script>"); //Yasaklı karakterler.
	if (strposa($_SERVER["QUERY_STRING"], $blockedQuerys, 1))
	    die($blockMessage);
	

	
}
?>

<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'zerohouse');
define('DB_PASSWORD', '*******');
define('DB_NAME', '******');
 
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("Oops, we ran into a problem here please try again later. -ZeroHouse " . $e->getMessage());
}
?>

 <?php
function GetRequest($url) { 
    $ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
    
    $output=curl_exec($ch); 
    curl_close($ch); 
    return $output; 
} 

?>
