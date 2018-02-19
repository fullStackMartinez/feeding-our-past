<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\FeedPast\Organization;

/**
 * api for signing up as an organization to Feeding Our Past
 *
 * @author Esteban Martinez
 * @author GKephart <GKephart@cnm.edu>
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSEION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab database connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");

	//determine with HTTP method was used

	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	if($method === "POST") {

		//decode the json and turn it into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//organization city address is required
		if(empty($requestObject->organizationAddressCity) === true) {
			throw(new \InvalidArgumentException("There is no city for organization", 405));
		}
		//organization state address is required
		if(empty($requestObject->organizationAddressState) === true) {
			throw(new \InvalidArgumentException("There is no state for organization", 405));
		}
		//organization street address is required
		if(empty($requestObject->organizationAddressStreet) === true) {
			throw(new \InvalidArgumentException("There is no state for organization", 405));
		}
		//organization zip code is required
		if(empty($requestObject->organizationAddressZip) === true) {
			throw(new \InvalidArgumentException("There is no zip code for organization", 405));
		}
		//the organization must indicate if donations are accepted
		if(empty($requestObject->organizationDonationAccepted) === true) {
			throw(new \InvalidArgumentException("Organization must indicate if donations are accepted", 405));
		}
		//organization email is required
		if(empty($requestObject->organizationEmail) === true) {
			throw(new \InvalidArgumentException("Organization email is not present", 405));
		}
		//organization operating hours are required
		if(empty($requestObject->organizationHoursOpen) === true) {
			throw(new \InvalidArgumentException("Operating hours not present", 405));
		}
		//organization name is required
		if(empty($requestObject->organizationName) === true) {
			throw(new \InvalidArgumentException("Organization name is not present", 405));
		}
		//organization password is required
		if(empty($requestObject->organizationPassword) === true) {
			throw(new \InvalidArgumentException("Must input valid password", 405));
		}
		//verify organization confirm password is present
		if(empty($requestObject->organizationPasswordConfirm) === true) {
			throw(new \InvalidArgumentException("Must input valid password", 405));
		}
		//organization phone number is required
		if(empty($requestObject->organizationPhone) === true) {
			throw(new \InvalidArgumentException("Organization phone number is not present", 405));
		}
		//organization url is not required, if empty set to null
		if(empty($requestObject->organizationUrl) === true) {
			$requestObject->organizationUrl = null;
		}
		//make sure the password and confirm password match
		if($requestObject->organizationPassword !== $requestObject->organizationPasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords do not match"));
		}

		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $requestObject->organizationPassword, $salt, 262144);

		$organizationActivationToken = bin2hex(random_bytes(16));

		//create the organization object and prepare it to be inserted into our database
		$organization = new Organization(generateUuidV4(), $organizationActivationToken, $requestObject->organizationAddressCity, $requestObject->organizationAddressState, $requestObject->organizationAddressStreet, $requestObject->organizationAddressZip, $requestObject->organizationDonationAccepted, $requestObject->organizationEmail, $hash, $requestObject->organizationHoursOpen, $requestObject->organizationLatX, $requestObject->organizationLongY, $requestObject->organizationName, $requestObject->organizationPhone, $salt, $requestObject->organizationUrl);

		//insert the organization into the database
		$organization->insert($pdo);

		//compose email message to send activation token to organization
		$messageSubject = ""


	}
	}