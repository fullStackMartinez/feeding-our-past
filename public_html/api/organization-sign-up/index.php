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

		//organization name is
	}
}