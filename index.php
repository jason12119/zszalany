<?php
error_reporting(E_ALL);
session_start();
ini_set('display_errors', 0);
error_reporting(0);

if($_SERVER['HTTP_HOST']=='localhost') {
	require_once 'classes/class.db_local.php';
}
else{
	require_once 'classes/class.db.php';
}
require_once 'classes/class.data.php';

$data = new data();
// HEADER =========================================================================
require_once "inc/head.php";


// PAGES CONDITIONS ===============================================================
if (!$_GET['page']) {
	require_once 'php/hp.php';
} else {
	require_once 'php/in.php';
}


// FOOTER =========================================================================
require_once 'inc/footer.php';
