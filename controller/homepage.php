<?php
/**
 * Created by PhpStorm.
 * User: laurel
 * Date: 2018/4/10
 * Time: 12:34
 */
/** para--0:show catagory;1:enter directory;2:back to last*/

function parseCatalog(){
	session_start();
	if (!empty($_SESSION['pwd'])) {
		unset($_SESSION['pwd']);
	}
    $order = "ls -l";
    $res = array();
	exec($order,$res);
    unset($res[0]);
    echo json_encode($res);
}
function enterDirectory(){
	session_start();
	$dirname = $_GET['dirname'];
	//save/update session
	if (isset($_SESSION['pwd'])) {
		$pwd = $_SESSION['pwd'];
		$newPath = $pwd."/".$dirname;
		$_SESSION['pwd'] = $newPath;
	}else{
		$_SESSION['pwd'] = $dirname;
	}
	$order = "ls -l ".$_SESSION['pwd'];
	$res = array();
	exec($order,$res);
	unset($res[0]);
	echo json_encode($res);
}
function backOne(){
	//get pwd from session
	session_start();
	$currentDirectory = $_SESSION['pwd'];
	$dir_arr = explode("/",$currentDirectory);
	$res = array();
	unset($dir_arr[count($dir_arr)-1]);
	$backone = implode("/",$dir_arr);
	$_SESSION['pwd'] = $backone;
	$order = "ls -l ".$backone;
	if ($backone=="") {
		unset($_SESSION['pwd']);
	}
	exec($order,$res);
	unset($res[0]);
	echo json_encode($res);
}
function catFile(){
	$filename = $_GET['filename'];
	session_start();
	$pwd = $_SESSION['pwd'];
	if (empty(trim($pwd))) {
		$order = "cat ".$filename;
	}else{
		$order = "cat ".$pwd.'/'.$filename;
	}
	$response = shell_exec($order);
    echo $response;
}
function download(){
	$url = $_GET['url'];
	$para = 'wget "http://php.net/get/php-7.0.2.tar.gz/from/a/mirror"';
	$order = '/var/www/html/hpc/controller/ipt '.$para;
	exec($order);
	echo "done";
}
function unzip(){
	$filename = $_GET['filename'];
	$suffix = $_GET['suffix'];
	session_start();
	$pwd = $_SESSION['pwd'];
	$order = "";
	if (empty(trim($pwd))) {
		$find_file = $filename;
		switch ($suffix) {
			case "zip":
				$order = "unzip ".$find_file;
				break;
			case "tar":
				$order = "tar –xvf ".$find_file;
				break;
			case "gz":
				$order = "tar -xzvf ".$find_file;
				break;
			case "bz2":
				$order = "tar -xjvf ".$find_file;
				break;
			case "z":
				$order = "tar –xZvf ".$find_file;
			default:
				break;
		}
	}else{
		$find_file = $pwd."/".$filename;
		switch ($suffix) {
			case "zip":
				$order = "unzip ".$find_file." -d ".$pwd;
				break;
			case "tar":
				$order = "tar –xvf ".$find_file." -C ".$pwd;
				break;
			case "gz":
				$order = "tar -xzvf ".$find_file." -C ".$pwd;
				break;
			case "bz2":
				$order = "tar -xjvf ".$find_file." -C ".$pwd;
				break;
			case "z":
				$order = "tar –xZvf ".$find_file." -C ".$pwd;
			default:
				break;
		}
	}	
	$ipt_order = '/var/www/html/hpc/controller/ipt "'.$order.'"';
	exec($ipt_order);
	$list = "ls -l ".$pwd;
    $res = array();
	exec($list,$res);
    unset($res[0]);
    echo json_encode($res);
}
function deleteFile(){
	$filename = $_GET['filename'];
	session_start();
	$pwd = $_SESSION['pwd'];
	if (empty(trim($pwd))) {
		$rm = "rm -f ".$filename;
	}else{
		$rm = "rm -f ".$pwd.'/'.$filename;
	}
	$order = '/var/www/html/hpc/controller/ipt "'.$rm.'"';
	exec($order);
    $list = "ls -l ".$pwd;
    $res = array();
	exec($list,$res);
    unset($res[0]);
    foreach ($res as $value) {
    	$f_arr = explode(" ",$value);
    	if ($filename==trim($f_arr[count($f_arr)-1])) {
    		echo "false";
    		return false;
    	}
    }
    echo json_encode($res);
}
function createDir(){
	$dirname = $_GET['dirname'];
	session_start();
	$pwd = $_SESSION['pwd'];
	if (empty(trim($pwd))) {
		$md = "mkdir ".$dirname;
	}else{
		$md = "mkdir ".$pwd.'/'.$dirname;
	}
	$order = '/var/www/html/hpc/controller/ipt "'.$md.'"';
	exec($order);
    $list = "ls -l ".$pwd;
    $res = array();
	exec($list,$res);
    unset($res[0]);
    echo json_encode($res);
}


switch ($_GET['para']) {
	case 0:
		//show catalog
		parseCatalog();
		break;
	case 1:
		//enter catalog
		enterDirectory();
		break;
	case 2:
		//back one step
		backOne();
		break;
	case 3:
		catFile();
		break;
	case 4:
		download();
		break;
	case 5:
		unzip();
		break;
	case 6:
		deleteFile();
		break;
	case 7:
		createDir();
		break;
	default:
		# code...
		break;
}
