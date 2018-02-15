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
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}


} catch() {


}