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