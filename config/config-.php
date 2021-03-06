<?php
date_default_timezone_set('America/New_York');

// database settings
$db_name = "OPEN-RECORDS-GENERATOR";

// $host = "http://o-r-g.com/";
$host = "http://".$_SERVER["HTTP_HOST"]."/";
$root = $_SERVER["DOCUMENT_ROOT"]."/";

$admin_path = $host . "open-records-generator/";
$admin_root = $root . "open-records-generator/";

$media_path = $host . "media/"; // don't forget to set permissions on this folder
$media_root = $root . "media/";

$models_root = $admin_root . "models/";

$lib_root = $admin_root . "lib/";

require_once($models_root."model.php");
require_once($models_root."objects.php");
require_once($models_root."wires.php");
require_once($models_root."media.php");
require_once($lib_root."lib.php");
require_once($lib_root."url-base.php");

$max_uploads = 5;
$m_pad = 5;
$resize = false;
$resize_scale = 65;
$resize_root = $media_root . "hi/";

// namespace stuff, for markdown parser
set_include_path($lib_root);
spl_autoload_register(function ($class) {
	$file = preg_replace('#\\\|_(?!.+\\\)#','/', $class) . '.php';
	if (stream_resolve_include_path($file))
		require $file;
});

// connect to database (called in head.php)
function db_connect($remote_user)
{
	$users = array();
	$creds = array();
	
	// full access
	$creds['full']['db_user'] = "reinfurt_42";
	$creds['full']['db_pass'] = "vNDEC89e";
	
	// read / write access 
	// (can't create / drop tables)
	$creds['rw']['db_user'] = "reinfurt_42_w";
	$creds['rw']['db_pass'] = "Rh5JrwEP";
	
	// read-only access
	$creds['r']['db_user'] = "reinfurt_42_r";
	$creds['r']['db_pass'] = "8hPxYMS9";
	
	// users -- should this be changed to a txt / csv file?
	$users["dfw"] = $creds['rw'];
	$users["main"] = $creds['rw'];
	$users["guest"] = $creds['r'];
	
	$host = "db153.pair.com";
	$dbse = "reinfurt_onrungo";
	$user = $users[$remote_user]['db_user'];
	$pass = $users[$remote_user]['db_pass'];
	
	$db = new mysqli($host, $user, $pass, $dbse);
	if($db->connect_errno)
		echo "Failed to connect to MySQL: " . $db->connect_error;
	return $db;
}
?>
