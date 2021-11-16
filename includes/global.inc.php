<?php
	/*ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);*/

	require_once 'classes/User.class.php';
	require_once 'classes/UserTools.class.php';
	require_once 'classes/DB.class.php';

	//connect to the database
	$db = new DB();
	$db->connect();

	//initialize UserTools object
	$userTools = new UserTools();

	//start the session
	session_start();

	//refresh session variables if logged in
	if(isset($_SESSION['logged_in'])) {
		$user = unserialize($_SESSION['user']);
		$_SESSION['user'] = serialize($userTools->get($user->id));
	}

?>