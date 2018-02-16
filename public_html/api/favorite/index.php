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
	$favoritevolunteerId= $id = filter_input(INPUT_GET, "favoritevolunteerId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$favoritePostId = $id = filter_input(INPUT_GET, "likeTweetId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//gets a specific favorite associated based on its composit key
		if ($favoritevolunteerId !== null && $favoritePostId !== null) {
			$favorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($pdo, $favoritevolunteerId, $favoritePostId);

			if($favorite!== null) {
				$reply->data = $favorite;
			}
			//if none of the search parameters are met throw an exception
		} else if(empty($favoritevolunteerId) === false) {
			$like = Favorite::getFavoriteByFavoriteVolunteerId($pdo, $favoritevolunteerId)->toArray();
			if($like !== null) {
				$reply->data = $like;
			}

			//get all the favorites associated with the post Id
		} else if(empty($favoritePostId) === false) {
			$favorite= Favorite::getFavoriteByFavoritePostId($pdo, $favoritePostId)->toArray();
			if($favorite !== null) {
				$reply->data = $favorite;
			}
		}