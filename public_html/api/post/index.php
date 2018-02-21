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
	$config = readConfig("/etc/apache2/capstone-mysql/feedkitty.ini");
	$cloudinary = json_decode($config["cloudinary"]);
	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);


	// make sure the id is valid
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new \InvalidArgumentException("id cannot be empty or negative", 402));
	}

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific post associated based on its primary key
		if(empty($id) === false) {
			$post = Post::getPostByPostId($pdo, $id);

			if($post !== null) {
				$reply->data = $post;
			}
		} else if(empty($postOrganizationId) === false) {
			$posts = Post::getPostByPostOrganizationId($pdo, $_SESSION["organization"]->getOrganizationId())->toArray();
			if($posts !== null) {
				$reply->data = $posts;
			}
		} else {
			$posts = Post::getPostByPostEndDateTime($pdo)->toArray();
			if($posts !== null) {
				$reply->data = $posts;
			}
		}
	} else if($method === "PUT" || $method === "POST") {
		if(empty($_SESSION["profile"]) === true) {

			throw (new \InvalidArgumentException("You Must Be Logged In to Post", 401));
		}

		verifyXsrf();
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($requestObject->postContent) === true) {
			throw (new \InvalidArgumentException("No Content In Post", 405));
		}
		if(empty($requestObject->postEndDateTime) === true) {
			$requestObject->postEndDateTime = null;
		}

		if($method === "PUT") {
			$post = Post::getPostByPostId($pdo, $id);
			if($post === null) {
				throw (new \RuntimeException("Post Does Not Exist", 404));
			}

			if(empty($_SESSION["post"]) === true || $_SESSION["post"]->getOrganizationId()
					->toString() !== $post->getPostOrganizationId()->toString()) {
				throw(new \InvalidArgumentException("You Are Not Allowed to Edit this Post", 403));
			}
//validateJwtHeader();
			// update all attributes
			//$post->setPostEndDateTime($requestObject->postEndDateTime);
			$post->setPostContent($requestObject->postContent);
			$post->update($pdo);
			// update reply
			$reply->message = "Post updated OK";
		} else if($method === "POST") {
			// enforce the user is signed in
			if(empty($_SESSION["organization"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to post", 403));
			}
			//enforce the end user has a JWT token
			//validateJwtHeader();
			// assigning variable to the user profile, add image extension
			$tempUserFileName = $_FILES["image"]["tmp_name"];
			// upload image to cloudinary and get public id
			$cloudinaryResult = \Cloudinary\Uploader::upload($tempUserFileName, array("width" => 500, "crop" => "scale"));
			// create new post and insert into the database
			$post = new Post(generateUuidV4(), $_SESSION["organization"]->getOrganizationId(), $requestObject->postContent, $requestObject->EndDateTime, $requestObject->postImageUrl, $requestObject->postStartDateTime, $requestObject->postTitle);
			$post->insert($pdo);
			// update reply
			$reply->message = "Post created OK";
		}
	} else if($method === "DELETE") {
		//enforce that the end user has a XSRF token.
		verifyXsrf();
		//retreive deleted post
		$post = Post::getPostbyPostID($pdo, $id);
		if($post === null) {
			throw(new RuntimeException("Post does not exisit", 404));
		}
		if(empty($_SESSION["organization"]) === true || $_SESSION["organization"]->getOrganizationId()->toString()) {
			throw(new \InvalidArgumentException("you are not allowed to delete this post", 403));
		}
		//enforce the end user has a JWT token
		//validateJwtHeader();
		//enforce the end user has a XSRF token.
		//elete post
		$post->delete($pdo);
		$reply->message = "Post Deleted";
	} else {
		throw (new InvalidArgumentException("invalid http request", 418));
	}

	//catch any exceptions that is thrown and update the reply status and message
} catch
(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);
