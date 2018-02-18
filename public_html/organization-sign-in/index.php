<?php
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\FeedPast\Organization;

/**
 * api for handling sign-in
 * @author JBrink			<JeffreyBrink@GMX.com>
 * @author Gkephart
 **/
		//prepare an empty reply
		$reply = new stdClass();
		$reply->status = 200;
		$reply->data = null;
		try {

			//start session
			if(session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
			}
			//grab mySQL statement
			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");

			//determine which HTTP method is being used
			$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];



			//If method is post handle the sign in logic
			if($method === "POST") {
				//make sure the XSRF Token is valid
				verifyXsrf();


				//process the request content and decode the json object into a php object
				$requestContent = file_get_contents("php://input");
				$requestObject = json_decode($requestContent);

				//check to make sure the password and email field is not empty
				if(empty($requestObject->organizationEmail) === true) {
					throw(new \InvalidArgumentException("Wrong email address.", 401));
				} else {
					$organizationEmail = filter_var($requestObject->organizationEmail, FILTER_SANITIZE_EMAIL);
				}

				if(empty($requestObject->organizationPassword) === true) {
					throw(new \InvalidArgumentException("Must enter a password.", 401));
				} else {
					$organizationPassword = $requestObject->organizationPassword;
				}

				//grab the organization profile from the database by the email provided
				$organization = Organization::getOrganizationByOrganizationEmail($pdo, $organizationEmail);
				if(empty($organization) === true) {
					throw(new \InvalidArgumentException("Invalid Email", 401));
				}

				//if the organization profile activation is not null throw an error
				if($organization->getOrganizationActivationToken() !== null){
					throw (new \InvalidArgumentException ("you are not allowed to sign in unless you have activated your account", 403));
				}

				//hash the password given to make sure it matches.
				$hash = hash_pbkdf2("sha512", $organizationPassword, $organization->getOrganizationSalt(), 262144);

				//verify hash is correct
				if($hash !== $organization->getOrganizationHash()) {
					throw(new \InvalidArgumentException("Password or email is incorrect.", 401));
				}

				//grab organization profile from database and put into a session
				$organization = Organization::getOrganizationByOrganizationId($pdo, $organization->getOrganizationId());

				$_SESSION["organization"] = $organization;


				//create the Auth payload
				$authObject = (object) [
					"organizationId" =>$organization->getOrganizationId(),
					"profileAtHandle" => $organization->getOrganizationName()
				];

				// create and set th JWT TOKEN
				setJwtAndAuthHeader("auth",$authObject);



				$reply->message = "Sign in was successful.";
			} else {
				throw(new \InvalidArgumentException("Invalid HTTP method request", 418));
			}
			// if an exception is thrown update the
		} catch(Exception | TypeError $exception) {
			$reply->status = $exception->getCode();
			$reply->message = $exception->getMessage();
		}
header("Content-type: application/json");
echo json_encode($reply);

