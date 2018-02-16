<?php


require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\ {
	Organization
};

/**
 * API for Organization
 *
 * @author Esteban Martinez
 * @author George Kephart
 * @version 1.0
 **/

//verify the session, and if the session isn't active, go ahead and start it
if(session_status() !==PHP_SESSION_ACTIVE) {
	session_start();
}
//set up an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//secure the MySQL connection to our database
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

	//determine the HTTP method
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$distance = filter_input(INPUT_GET, "distance", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
	$organizationEmail = filter_input(INPUT_GET, "organizationEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$organizationName = filter_input(INPUT_GET, "organizationName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$userLatX = filter_input(INPUT_GET, "userLatX", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
	$userLongY = filter_input(INPUT_GET, "userLongY", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);


	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("sorry but your ID cannot be empty or negative", 405));
	}

	if($method === "GET") {
		//set the XSRF cookie
		setXsrfCookie();

		//gets an organization starting by their id and if not, the next method
		if(empty($id) === false) {
			$organization = Organization::getOrganizationByOrganizationId($pdo, $id);
			if($organization !== null) {
				$reply->data = $organization;
			}
		} else if(empty($organizationEmail) === false) {
			$organization = Organization::getOrganizationByOrganizationEmail($pdo, $organizationEmail);
			if($organization !== null) {
				$reply->data = $organization;
			}
		} else if(empty($organizationName) === false) {
			$organization = Organization::getOrganizationByOrganizationName($pdo, $organizationName);
			if($organization !== null) {
				$reply->data = $organization;
			}
		}
	} elseif($method === "PUT") {
		//enforce that the XSRF token is present in the header
		verifyXsrf();

		//enforce the end user is signed in and only trying to edit their own profile
		if(empty($_SESSION["organization"]) === true || $_SESSION["organization"]->getOrganizationId()->toString() !== $id) {
			throw(new \InvalidArgumentException("Sorry but you are not allowed to access this profile", 403));
		}

		//enforce the end user has a JWT token
		validateJWTHeader();

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//retrieve the organizations profile that will be updated

	}

}