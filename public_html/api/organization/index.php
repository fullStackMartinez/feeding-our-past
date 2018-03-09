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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");

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
		throw(new \InvalidArgumentException("sorry but your ID cannot be empty or negative", 405));
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
		} else if(empty($distance) === false || (empty($userLatX) === false) || (empty($userLongY) === false)) {
			$organization = Organization::getOrganizationByDistance($pdo, $distance, $userLatX, $userLongY);
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
		$organization = Organization::getOrganizationByOrganizationId($pdo, $id);
		if($organization === null) {
			throw(new \RunTimeException("Sorry but organization profile does not exist", 404));
		}
		//COMMENTED OUT UPDATE BY LOCATION PER BRIDGE SUGGESTION
		//organization address city
		if(empty($requestObject->organizationAddressCity) === true) {
			throw(new \InvalidArgumentException("Sorry, there is no organization city address", 405));
		}
		//organization address state
		if(empty($requestObject->organizationAddressState) === true) {
			throw(new \InvalidArgumentException("Sorry, there is no organization state address", 405));
		}
		//organization street address
		if(empty($requestObject->organizationAddressStreet) === true) {
			throw(new \InvalidArgumentException("Sorry, there is no organization street address", 405));
		}
		//organization address zip code
		if(empty($requestObject->organizationAddressZip) === true) {
			throw(new \InvalidArgumentException("Sorry, there is no organization zip code", 405));
		}
		//organization donation accepted
		if(empty($requestObject->organizationDonationAccepted) === true) {
			throw(new \InvalidArgumentException("sorry, we are not sure if donations are accepted", 405));
		}
		//organization email
		if(empty($requestObject->organizationEmail) === true) {
			throw(new \InvalidArgumentException("Sorry, but no organization email is present", 405));
		}
		//organization hours of operation
		if(empty($requestObject->organizationHoursOpen) === true) {
			throw(new \InvalidArgumentException("Sorry, no hours of operation present", 405));
		}
		//organization name
		if(empty($requestObject->organizationName) === true) {
			throw(new \InvalidArgumentException("Sorry, no organization name present", 405));
		}
		//organization phone
		if(empty($requestObject->organizationPhone) === true) {
			throw(new \InvalidArgumentException("Sorry, no phone number present", 405));
		}
		//organization url
		if(empty($requestObject->organizationUrl) === true) {
			$requestObject->OrganizationUrl = $organization->getOrganizationUrl();
		}

		//COMMENTED OUT LOCATION
		$organization->setOrganizationAddressCity($requestObject->organizationAddressCity);
		$organization->setOrganizationAddressState($requestObject->organizationAddressState);
		$organization->setOrganizationAddressStreet($requestObject->organizationAddressStreet);
		$organization->setOrganizationAddressZip($requestObject->organizationAddressZip);
		$organization->setOrganizationDonationAccepted($requestObject->organizationDonationAccepted);
		$organization->setOrganizationEmail($requestObject->organizationEmail);
		$organization->setOrganizationHoursOpen($requestObject->organizationHoursOpen);
		$organization->setOrganizationName($requestObject->organizationName);
		$organization->setOrganizationPhone($requestObject->organizationPhone);
		$organization->setOrganizationUrl($requestObject->organizationUrl);
		$organization->update($pdo);

		//update reply
		$reply->message = "Organization profile information updated";

		} elseif($method === "DELETE") {

		//verify the XSRF token
		verifyXsrf();

		$organization = Organization::getOrganizationByOrganizationId($pdo, $id);
		if($organization === null) {
			throw (new \RuntimeException("Organization profile does not exist"));
		}

		//enforce the organization is signed in and only trying to edit their own profile
		if(empty($_SESSION["organization"]) === true || $_SESSION["organization"]->getOrganizationId()->toString() !==$organization->getOrganizationId()->toString()) {
			throw(new \InvalidArgumentException("Sorry, but you are not allowed to access this profile", 403));
		}

		//enforce the end user has a JWT token
		validateJwtHeader();

		//delete the organization from the database
		$organization->delete($pdo);
		$reply->message = "Organization Successfully Deleted";
	} else {
		throw (new \InvalidArgumentException("Invalid HTTP request", 400));
	}
	//catch any exceptions that were thrown and update the status and message state variable field

} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

//encode and return reply to front end caller
echo json_encode($reply);