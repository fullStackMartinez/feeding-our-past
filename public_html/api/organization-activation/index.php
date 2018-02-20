<?php
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\Organization;

/**
 * API to check organization activation status
 * @auther JBrink  <JeffreyBrink@GMX.com>
 * @author Gkephart
 */
// Check the session. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	// grab the MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");
	//check the HTTP method being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input (never trust the end user)
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING);


	// make sure the activation token is the correct size
	if(strlen($activation) !== 32) {
		throw(new InvalidArgumentException("activation has an incorrect length", 405));
	}

	// verify that the organization activation token is a string value of a hexadeciaml
	if(ctype_xdigit($activation) === false) {
		throw (new \InvalidArgumentException("activation is empty or has invalid contents", 405));
	}
	// handle The GET HTTP request
	if($method === "GET") {

		// set XSRF Cookie
		setXsrfCookie();

		//find organization profile associated with the activation token
		$organization = Organization::getOrganizationByOrganizationActivationToken($pdo, $activation);
		//verify the profile is not null
		if($organization !== null) {

			//make sure the organization activation token matches
			if($activation === $organization->getOrganizationActivationToken()) {

				//set activation to null
				$organization->setOrganizationActivationToken(null);

				//update the profile in the database
				$organization->update($pdo);

				//set the reply for the end user
				$reply->data = "Thank you for activating your account, you will be auto-redirected to your profile shortly.";

				//compose email subject that will be sent to interested organization
				$messageSubject = "Great News! You have been approved to join Feeding Our Past";
				//compose message to send with email
				$message = <<< EOF
 <h2>Welcome to Feeding Our Past!</h2>
 <p>Our team has reviewed your request to join our site, and we are pleased to announce that you have been added to our community! It is our honor to be working with an organization who joins our passion and dedication to helping stop Senior Hunger in Albuquerque. Feel free to check out our community and make/edit posts that will advertise upcoming events so that our volunteers can see and express their interest!</p>
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
				$recipients = [$organization->getOrganizationEmail()];

				//set the recipient to the swift message
				$swiftMessage->setTo($recipients);
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
			}
		} else {
			//throw an exception if the activation token does not exist
			throw(new \RuntimeException("Profile with this activation code does not exist", 404));
		}
	} else {
		// throw an exception if the4 HTTP request is not a GET
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














