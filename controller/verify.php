<?php 
$account = include("../conf/conf.php");
session_start();
if ($_GET['account']==$account['account']&&$_GET['password']==$account['pwd']) {
	$_SESSION['login'] = 1;
	echo 1;
}else{
	echo -1;
}

?>