<?php
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\Volunteer;

/**
 * API to handle volunteer sign-in
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Gkephart
 **/

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// verify the session, start if not active
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");

	// determine which HTTP method was used
	// shorthand: $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	if(array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER)) {
		$method = $_SERVER["HTTP_X_HTTP_METHOD"];
	} else {
		$method = $_SERVER["REQUEST_METHOD"];
	}

	if($method === "POST") {

		// make sure the XSRF Token is valid
		verifyXsrf();

		// process the request content and decode the json object into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// check to make sure the password and email fields are not empty
		if(empty($requestObject->volunteerEmail) === true) {
			throw(new \InvalidArgumentException("Must enter an email address.", 401));
		} else {
			$volunteerEmail = filter_var($requestObject->volunteerEmail, FILTER_SANITIZE_EMAIL);
		}

		if(empty($requestObject->volunteerPassword) === true) {
			throw(new \InvalidArgumentException("Must enter a password.", 401));
		} else {
			$volunteerPassword = $requestObject->volunteerPassword;
		}





	}

}