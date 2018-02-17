<?php

require_once dirname(__DIR__,3)."/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\Volunteer;

/**
 * API to check volunteer profile activation status
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Gkephart
 **/

// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");

	// determine which HTTP method was used
	// shorthand: $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	if(array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER)) {
		$method = $_SERVER["HTTP_X_HTTP_METHOD"];
	} else {
		$method = $_SERVER["REQUEST_METHOD"];
	}

	// sanitize input (never trust the end user)
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING);

	// make sure the activation token is the correct size
	if(strlen($activation) !== 32) {
		throw(new \InvalidArgumentException("activation has an incorrect length", 405));
	}

	//verify that the activation token is a string value of a hexadecimal
	if(ctype_xdigit($activation) === false) {
		throw(new \InvalidArgumentException("activation is empty or has invalid contents", 405));
	}

	if($method === "GET") {

		// set XSRF Cookie
		setXsrfCookie();

		// find volunteer profile associated with the activation token
		$volunteer = Volunteer::getVolunteerByVolunteerActivationToken($pdo, $activation);

		// verify the volunteer profile is not null
		if($volunteer !== null) {

			// make sure the activation token matches
			if($activation === $volunteer->getVolunteerActivationToken()) {

				// set activation token to null
				$volunteer->setVolunteerActivationToken(null);

				//update the volunteer profile in the database
				$volunteer->update($pdo);

				// set the reply for the end user
				$reply->data = "Thank you for activating your account, you will be auto-redirected to your volunteer profile shortly.";
			}
		} else {
			// throw an exception if the activation token does not exist
			throw(new \RuntimeException("A volunteer profile with this activation code does not exist", 404));
		}
	} else {
		// throw an exception if the HTTP request is not a GET
		throw(new \InvalidArgumentException("Invalid HTTP method request", 403));
	}

	// update the reply object's status and message state variables if an exception or type exception was thrown
} catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

// prepare and send the reply
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);