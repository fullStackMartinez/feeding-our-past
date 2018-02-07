<?php
namespace Edu\Cnm\FeedPast;

use Edu\Cnm\FeedPast\Volunteer;

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Volunteer class
 *
 * This is a complete PHPUnit test of the Volunteer class. It is complete because all mySQL/PDO methods
 * are tested for both invalid and valid inputs.
 *
 * @see Volunteer
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
