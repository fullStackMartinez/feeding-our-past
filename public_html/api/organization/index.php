<?php


require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\FeedPast\ {
	Organization
};

/**
 * API for Organization
 *
 * @author Esteban Martinez
 * @author George Kephart
 * @version 1.0
 **/

//verify the session, and if the session isn't active, go ahead and start it
if(session_status() !==PHP_SESSION_ACTIVE) {
	session_start();
}
//set up an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;