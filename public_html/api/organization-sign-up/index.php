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
		$messageSubject = "This organization, $organization, is interested in helping";

		//build activation link that can travel to another server and still work. This link will confirm the organization account.
		//make sure url is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);

		//create path
		$urlglue = $basePath . "/api/activation/?activation=" . $organizationActivationToken;

		//create the redirect link
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlglue;

		//compose message to send with email
		$message = <<< EOF
 <h2>Welcome to Feeding Our Past!</h2>
 <p>This organization is interested in helping</p>
 <p><a href="$confirmLink">$confirmLink</a></p>
EOF;
		//create swift email
		$swiftMessage = new Swift_Message();

		//attach the sender to the message
		//this takes the form of an associative array where the email is the key to a real name
		$swiftMessage->setFrom(["feedingourpast@gmail.com" => "Feeding Our Past"]);

		/**
		 * attach recipients to the message
		 * notice this is an array that can include or omit the recipient's name
		 * use the recipient's real name where possible;
		 * this reduces the probability that the email will be marked as spam
		 **/
		//define who the recipient is
		$recipients = ["feedingourpast@gmail.com" => "Feeding Our Past"];

		//set the recipient to the swift message

		//attach the subject line to the email message
		$swiftMessage->setSubject($messageSubject);

		/**
		 * attach the message tot he email
		 * set two versions of the message: a html formatted version and a filter_var()ed version of the message, which is just plain text
		 * notice the tactic used is to display the entire $confirmLink to plain text
		 * This lets users who are not viewing the html content to still access the link
		 **/
		//attach the html version of the message
		$swiftMessage->setBody($message, "text/html");

		//attach the plain text version of the message
		$swiftMessage->addPart(html_entity_decode($message), "text/plain");

		/**
		 * send the email via SMTP; the SMTP server here is configured to relay everything upstream via CNM
		 * this default may or may not be available on all web hosts; consult their documentation/support for details
		 * swiftMailer supports many different transport methods; SMTP was chosen because it's the most compatible and has the best error handling
		 * @see http://swiftmailer.org/docs/sending.html Sending Messages - Documentation - SwitftMailer
		 **/
		//setup SMTP
		$smtp = new Swift_SmtpTransport("localhost", 25);
		$mailer = new Swift_Mailer($smtp);

		//send the message
		$numSent = $mailer->send($swiftMessage, $failedRecipients);

		/**
		 * the send method returns the number of recipients that accepted the Email
		 * so, if the number attempted is not the number accepted, this is an Exception
		 **/
		/*if($numSent !== count($recipients)) {
			// the $failedRecipients parameter passed in the send() method now contains contains an array of the Emails that failed
			throw(new RuntimeException("unable to send email", 400));
		} **/
//THIS IS WHERE WE PUT THE NEW OBJECT FOR ORGANIZATIONS EMAIL, THE EMAIL THEY WILL GET WITHOUT ACTIVATION LINK
		//compose email subject that will be sent to interested organization
		$messageSubject = "Thank you for your interest in Feeding Our Past. Your account is awaiting approval";
		//compose message to send with email
		$message = <<< EOF
 <h2>Welcome to Feeding Our Past!</h2>
 <p>Thank You for your interest in helping stomp out Senior Hunger in the Albuquerque area. We have received your profile request and will contact you shortly with confirmation that your profile is ready to go. Thank you!</p>
 <p>Sincerely, <br> The Feeding Our Past Development Team</p>
EOF;
		//create swift email
		$swiftMessage = new Swift_Message();

		//attach the sender to the message
		//this takes the form of an associative array where the email is the key to a real name
		$swiftMessage->setFrom(["feedingourpast@gmail.com" => "Feeding Our Past"]);

		/**
		 * attach recipients to the message
		 * notice this is an array that can include or omit the recipient's name
		 * use the recipient's real name where possible;
		 * this reduces the probability that the email will be marked as spam
		 **/
		//define who the recipient is
		$recipients = [$requestObject->organizationEmail];

		//set the recipient to the swift message

		//attach the subject line to the email message
		$swiftMessage->setSubject($messageSubject);

		/**
		 * attach the message tot he email
		 * set two versions of the message: a html formatted version and a filter_var()ed version of the message, which is just plain text
		 * notice the tactic used is to display the entire $confirmLink to plain text
		 * This lets users who are not viewing the html content to still access the link
		 **/
		//attach the html version of the message
		$swiftMessage->setBody($message, "text/html");

		//attach the plain text version of the message
		$swiftMessage->addPart(html_entity_decode($message), "text/plain");

		/**
		 * send the email via SMTP; the SMTP server here is configured to relay everything upstream via CNM
		 * this default may or may not be available on all web hosts; consult their documentation/support for details
		 * swiftMailer supports many different transport methods; SMTP was chosen because it's the most compatible and has the best error handling
		 * @see http://swiftmailer.org/docs/sending.html Sending Messages - Documentation - SwitftMailer
		 **/
		//setup SMTP
		$smtp = new Swift_SmtpTransport("localhost", 25);
		$mailer = new Swift_Mailer($smtp);

		//send the message
		$numSent = $mailer->send($swiftMessage, $failedRecipients);

		/**
		 * the send method returns the number of recipients that accepted the Email
		 * so, if the number attempted is not the number accepted, this is an Exception
		 **/
		/*if($numSent !== count($recipients)) {
			// the $failedRecipients parameter passed in the send() method now contains contains an array of the Emails that failed
			throw(new RuntimeException("unable to send email", 400));
		} **/
		//update reply
		$reply->message = "Thank you for your interest in requesting an account with Feeding Our Past";
		} else {
		throw (new \InvalidArgumentException("invalid HTTP request"));
	}
	} catch(\Exception |\TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
}

header("Content-type: application/json");
echo json_encode($reply);