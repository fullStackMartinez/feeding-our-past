<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\Volunteer;

/**
 * api for signing up as a Volunteer to Stop Senior Hunger
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Gkephart <GKephart@cnm.edu>
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

	if($method === "POST") {
		// decode the json and turn it into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// volunteer profile availability (can be null, if empty set it to null)
		if(empty($requestObject->volunteerAvailability) === true) {
			$requestObject->volunteerAvailability = null;
		}

		// volunteer profile email (required)
		if(empty($requestObject->volunteerEmail) === true) {
			throw(new \InvalidArgumentException("No volunteer email present", 405));
		}

		// volunteer profile name (required)
		if(empty($requestObject->volunteerName) === true) {
			throw(new \InvalidArgumentException("No volunteer name present", 405));
		}

		// volunteer profile password (required)
		if(empty($requestObject->volunteerPassword) === true) {
			throw(new\InvalidArgumentException("Must input valid password", 405));
		}

		// verify that the confirm password is present (required)
		if(empty($requestObject->volunteerPasswordConfirm) === true) {
			throw(new \InvalidArgumentException("Must input valid password", 405));
		}

		// volunteer profile phone (required)
		if(empty($requestObject->volunteerPhone) === true) {
			throw(new \InvalidArgumentException("No volunteer phone number present", 405));
		}

		// made sure the password and confirm password match
		if($requestObject->volunteerPassword !== $requestObject->volunteerPasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords do not match"));
		}

		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $requestObject->volunteerPassword, $salt, 262144);

		$volunteerActivationToken = bin2hex(random_bytes(16));

		// create the volunteer object and prepare to insert into the database
		$volunteer = new Volunteer(generateUuidV4(), $volunteerActivationToken, $requestObject->volunteerAvailability, $requestObject->volunteerEmail, $hash, $requestObject->volunteerName, $requestObject->volunteerPhone, $salt);

		// insert the volunteer profile into the database
		$volunteer->insert($pdo);

		// compose the email message to send with the activation token
		$messageSubject = "One step closer to Feeding Our Past -- Account Activation";

		// building the activation link that will be clicked to confirm the account
		// make sure URL is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);

		// create the path
		$urlglue = $basePath . "/api/activation/?activation=" . $volunteerActivationToken;

		// create the redirect link
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlglue;

		// compose message to send with email
		$message = <<< EOF
<h2>Welcome to Feeding Our Past.</h2>
<p>Thank you for your interest in our initiative to help stop senior hunger.</p>
<p>In order to continue with a volunteer profile, you must confirm your account.</p>
<p><a href="$confirmLink">$confirmLink</a></p>
EOF;

		// create swift email
		$swiftMessage = new Swift_Message();

		// attach the sender to the message
		// this takes the form of an associative array where the email is the key to a real name
		$swiftMessage->setFrom(["?? TO DO ??"]);

		/**
		 * attach recipients to the message - this is an array that can include or omit the recipient's name
		 * use the real name where possible to reduce the possibility of the email being marked as spam
		 **/
		// define who the recipient is
		$recipients = [$requestObject->volunteerEmail];

		// attach the subject line to the email message
		$swiftMessage->setSubject($messageSubject);

		/**
		 * attach the message to the email
		 * set two versions of the message: a html formatted version and a filter_var()ed version, plain text
		 * the tactic is to display the entire $confirmLink to plain text
		 * this lets users who are not viewing the html content to still access the link
		 **/
		// attach the html version of the message
		$swiftMessage->setBody($message, "text/html");

		// attach the plain text version of the message
		$swiftMessage->addPart(html_entity_decode($message), "text/plain");

		/**
		 * send the email via SMTP; the SMTP server is configured to relay everything upstream via CNM
		 * this default may or may not be available on all web hosts; consult their documentation/support for details
		 * SwiftMailer supports many different transport methods; SMTP was chosen because it's the most compatible and has the best error handling
		 * @see http://swiftmailer.org/docs/sending.html Sending Messages - Documentation - SwitftMailer
		 **/
		// setup smtp
		$smtp = new Swift_SmtpTransport(
			"localhost", 25);
		$mailer = new Swift_Mailer($smtp);

		// send the message
		$numSent = $mailer->send($swiftMessage, $failedRecipients);

		// update reply
		$reply->message = "Thank you for creating a volunteer profile with Feeding Our Past";

	} else {
		throw(new \InvalidArgumentException("invalid http request"));
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
}

header("Content-type: application/json");
echo json_encode($reply);