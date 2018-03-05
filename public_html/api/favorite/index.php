<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";


use Edu\Cnm\FeedPast\{
			Favorite
};

/**
 * api for the Favorite class
 ** @author George Kephart
 * @author Jeffrey Brink  JeffreyBrink@GMX.com
 */

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
}

//prepare an empty reply
$reply = new stdclass();
$reply->status = 200;
$reply->data = null;

try {
			$pdo = connectToEncryptedMySql("/etc/apache2/capstone-mysql/feedkitty.ini");

			//determine which HTTP method was used
			$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"]: $_SERVER["REQUEST_METHOD"];


	//sanitize the search parameters
	$favoriteVolunteerId= $id = filter_input(INPUT_GET, "favoriteVolunteerId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$favoritePostId = $id = filter_input(INPUT_GET, "favoritePostId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//gets a specific favorite associated based on its composit key
		if ($favoriteVolunteerId !== null && $favoritePostId !== null) {
			$favorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($pdo, $favoritePostId, $favoriteVolunteerId);

			if($favorite!== null) {
				$reply->data = $favorite;
			}
			//if none of the search parameters are met throw an exception
		} else if(empty($favoriteVolunteerId) === false) {
			$like = Favorite::getFavoriteByFavoriteVolunteerId($pdo, $favoriteVolunteerId)->toArray();
			if($like !== null) {
				$reply->data = $like;
			}

			//get all the favorites associated with the post Id
		   } else if(empty($favoritePostId) === false) {
			$favorite = Favorite::getFavoriteByFavoritePostId($pdo, $favoritePostId)->toArray();

			if($favorite !== null) {
				$reply->data = $favorite;
			}
		} else {
					throw new InvalidArgumentException("incorrect search parameters ", 404);
		}


	} else if($method === "POST" || $method === "PUT") {

				//decide the response from the front end
				$requestContent = file_get_contents("php://input");
				$requestObject = json_decode($requestContent);

		if(empty($requestObject->favoriteVolunteerId) === true) {
			throw (new \InvalidArgumentException("No Volunteer linked to the Favorite", 405));
		}

		if(empty($requestObject->favoritePostId) === true) {
			throw (new \InvalidArgumentException("No post linked to the Favorite", 405));
		}

		if($method === "POST") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token
			//validateJwtHeader();

			// enforce the user is signed in
			if(empty($_SESSION["volunteer"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to favorite posts", 403));
			}

			//validateJwtHeader();

			$favorite= new Favorite($_SESSION["volunteer"]->getVolunteerId(), $requestObject->favoritePostId);
			$favorite->insert($pdo);
			$reply->message = "post favorited successfully";


		} else if($method === "PUT") {

			//enforce the end user has a XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token
			//validateJwtHeader();

			//grab the favorite by its composite key
			$favorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($pdo, $requestObject->favoriteVolunteerId, $requestObject->fovoritePostId);
			if($favorite=== null) {
				throw (new \RuntimeException("Favorite does not exist"));
			}

			//enforce the user is signed in and only trying to edit their own like
			if(empty($_SESSION["volunteer"]) === true || $_SESSION["volunteer"]->getVolunteerId() !== $favorite->getFavoriteVolunteerId()) {
				throw(new \InvalidArgumentException("You are not allowed to delete this post", 403));
			}

			//validateJwtHeader();

			//preform the actual delete
			$favorite->delete($pdo);

			//update the message
			$reply->message = "Favorite successfully deleted";
		}

			// if any other HTTP request is sent throw an exception
		} else {
			throw new \InvalidArgumentException("invalid http request", 400);
		}
			//catch any exceptions that are thrown and update the reply status and message
			} catch(\Exception | \TypeError $exception) {
				$reply->status = $exception->getCode();
				$reply->message = $exception->getMessage();
		}

		header("Content-type: application/json");
		if($reply->data === null) {
		unset($reply->data);
}
		// encode and return reply to front end caller
		echo json_encode($reply);

