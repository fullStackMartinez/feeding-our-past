<?php
namespace Edu\Cnm\FeedPast\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\QueryDataSet;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\Operation\{Composite, Factory, Operation};

// grab the encrypted properties file
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// autoload Composer packages
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

/**
 * Abstract class containing universal and project specific mySQL parameters.
 * Designed to lay foundation of the unit tests for this project. It loads all of the
 * database parameters about the project so that table specific tests can share the
 * parameters in one place.
 *
 * Note: Tables should be added in the order they were created.
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
