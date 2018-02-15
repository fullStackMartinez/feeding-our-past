<?php

require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");

require_once("etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\ {Volunteer};

/**
 * API for Volunteer
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Gkephart
 */

// verify the session, start it if not already active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("etc/apache2/capstone-mysql/feedkitty.ini");

	// determine HTTP method used
	// shorthand: $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	if(array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER)) {
		$method = $_SERVER["HTTP_X_HTTP_METHOD"];
	} else {
		$method = $_SERVER["REQUEST_METHOD"];
	}

	// sanitize the input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$volunteerEmail = filter_input(INPUT_GET, "volunteerEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$volunteerName = filter_input(INPUT_GET, "volunteerName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new \InvalidArgumentException("id cannot be empty or negative", 405));
	}

	if($method === "GET") {
		// set XSRF cookie
		setXsrfCookie();

		// get a volunteer by id
		if(empty($id) === false) {
			$volunteer = Volunteer::getVolunteerByVolunteerId($pdo, $id);
			if($volunteer !== null) {
				$reply->data = $volunteer;
			}
		} else if(empty($volunteerEmail) === false) {
			$volunteer = Volunteer::getVolunteerByVolunteerEmail($pdo, $volunteerEmail);
			if($volunteer !== null) {
				$reply->data = $volunteer;
			}
		} else if(empty($profileName) === false) {
			$volunteer = Volunteer::getVolunteerByVolunteerName($pdo, $volunteerName);
			if($volunteer !== null) {
				$reply->data = $volunteer;
			}
		}
	} else if($method === "PUT") {
		// enforce that the XSRF token is present in the header
		verifyXsrf();

		// enforce the volunteer is signed in and only trying to edit their own info
		if(empty($_SESSION["volunteer"]) === true || $_SESSION["volunteer"]->getVolunteerId()->toString() !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 403));
		}

		// enforce the volunteer has a JWT token
		validateJwtHeader();

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// retrieve the volunteer profile to be updated
		$volunteer = Volunteer::getVolunteerByVolunteerId($pdo, $id);
		if($volunteer === null) {
			throw(new \RuntimeException("Profile does not exist", 404));
		}

		// volunteer profile availability (can be null)
		if(empty($requestObject->volunteerAvailability) === true) {
			$requestObject->volunteerAvailability = $volunteer->getVolunteerAvailability();
		}

		// volunteer profile email (required)
		if(empty($requestObject->volunteerEmail) === true) {
			throw(new \InvalidArgumentException("No profile email present", 405));
		}

		// volunteer profile name (required)
		if(empty($requestObject->volunteerName) === true) {
			throw(new \InvalidArgumentException("No profile name present", 405));
		}

		// volunteer profile phone (required)
		if(empty($requestObject->volunteerPhone) === true) {
			throw(new \InvalidArgumentException("No profile phone number present", 405));
		}

		// update the volunteer profile information
		$volunteer->setVolunteerAvailability($requestObject->volunteerAvailability);
		$volunteer->setVolunteerEmail($requestObject->volunteerEmail);
		$volunteer->setVolunteerName($requestObject->volunteerName);
		$volunteer->setVolunteerPhone($requestObject->volunteerPhone);
		$volunteer->update($pdo);

		// update reply message
		$reply->message = "Volunteer profile information updated";

	}


} catch() {


}