<?php
/**
 * Created by PhpStorm.
 * User: petersdata
 * Date: 2/15/18
 * Time: 3:17 PM
**/

require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * api for signing out
 * @author Gkephart
 * @author Peter Street <peterbstreet@gmail.com
 * @version 1.0
**/

//This sets the PHP session to active at the start of the session
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}
/**
 * This creates a new standard class named reply
 * reply is set to a status code of 200 which is the standard response for successful HTTP requests
 * status 200 is defined on the server side as well as the API
 * The reply variable state variable data is set to null
**/
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//Connect to MySql
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");
	//determine which HTTP method was used using the superglobal server variable
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//if superglobal session get variable returns an empty set let the user know they are signed out
	if($method === "GET") {
		$_SESSION = [];
		$reply->message = "You are now signed out.";
	}
	//if the superglobal session is not empty but the superglobal server is ? then throw exception
	else {
		throw (new \InvalidArgumentException("Invalid HTTP method request"));
	}
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
//encode and return reply to front end caller
echo json_encode($reply);