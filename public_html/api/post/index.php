<?php

/**
 * Created by PhpStorm.
 * User: petersdata
 * Date: 2/15/18
 * Time: 3:18 PM
 */

/**
 * Here we load the packages required for this API
 */
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";

use Edu\Cnm\FeedPast\{
	Post,
	/**
	 * Use Organization class for testing purposes only
	 **/
	Organization
};

/**
 * Api for the Post class
 * @author Peter Street <peterBStreet@gmail.com>
 * @author george kephart
 */

//Start the session if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//Connect to encrypted MySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//sanitize the search parameters
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$postOrganizationId = filter_input(INPUT_GET, "postOrganizationId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$postContent = filter_input(INPUT_GET, "postContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$postEndDateTime = filter_input(INPUT_GET, "postEndDateTime", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$postImageUrl = filter_input(INPUT_GET, "postImageUrl", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$postStartDateTime = filter_input(INPUT_GET, "postStartDateTime", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$postTitle = filter_input(INPUT_GET, "postTitle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new \InvalidArgumentException("id cannot be empty or negative", 402));
	}

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific post associated based on its primary kdy
		if(empty($id) === false) {
			$post = Post::getPostByPostId($pdo, $id);
			if($post !== null) {
				$reply->data = $post;
			}
		} else if(empty($postOrganizationId) === false) {
			$post = post::getPostByPostOrganizationId($pdo, $postOrganizationId)->toArray();
			if($post !== null) {
				$reply->data = $post;
			}
		} else if(empty($postEndDateTime) === false) {
			$post = post::getPostByPostEndDateTime($pdo, $postEndDateTime);
			if($post !== null) {
				$reply->data = $post;
			}


		} else {
			throw new InvalidArgumentException("incorrect search parameters ", 404);
		}

		validateJwtHeader();

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($requestObject->postOrganizationId) === true) {
			throw (new \InvalidArgumentException("No Organization linked to the Post", 405));
		}
		if(empty($requestObject->postId) === true) {
			throw (new \InvalidArgumentException("No Post linked to the Id", 405));
		}
		if(empty($requestObject->postEndDateTime) === true) {
			$requestObject->PostEndDateTime = date("y-m-d H:i:s");
		}
		if($method === "POST") {
			//enforce that the end user has a XSRF token.
			verifyXsrf();
			//enforce the end user has a JWT token
			//validateJwtHeader();
			// enforce the user is signed in
			if(empty($_SESSION["post"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to posts", 403));
			}
			//validateJwtHeader();
			$like = new Like($_SESSION["post"]->getpostId(), $requestObject->postOrganizationId);
			$like->insert($pdo);
			$reply->message = "Post successful";
		} else if($method === "PUT") {
			//enforce the end user has a XSRF token.
			verifyXsrf();
			//enforce the end user has a JWT token
			//validateJwtHeader();
			//grab the like by its composite key
			$like = Post::getPostByPostIdAndPostOrganizationId($pdo, $requestObject->postId, $requestObject->postOrganizationId);
			if($like === null) {
				throw (new RuntimeException("Post does not exist"));
			}
			//enforce the user is signed in and only trying to edit their own like
			if(empty($_SESSION["post"]) === true || $_SESSION["post"]->getPostId() !== $like->getPostId()) {
				throw(new \InvalidArgumentException("You are not allowed to delete this post", 403));
			}
			//validateJwtHeader();
			//preform the actual delete
			$like->delete($pdo);
			//update the message
			$reply->message = "Post has been successfully deleted";
		}
		// if any other HTTP request is sent throw an exception
	} else {
		throw new \InvalidArgumentException("invalid http request", 400);
	}
	//catch any exceptions that is thrown and update the reply status and message
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
/**
 * Created by PhpStorm.
 * User: petersdata
 * Date: 2/15/18
 * Time: 3:18 PM
 */