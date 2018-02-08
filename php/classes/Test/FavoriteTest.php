<?php
namespace Edu\Cnm\Feedpast\Test;

use Edu\Cnm\feedpast\{Favorite, Organization, Volunteer};

// grab the class under scrunity
require_once(dirname(__DIR__,2) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__,) . "lib/uuid.php");

/**
 * Full PhpUnit test for the favorites class.  It is complete because ALL mySQL/PDO enabled methods
 * are tested for both valid and invalid inputs.
 */