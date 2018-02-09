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
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");


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
abstract class FeedPastTest extends TestCase {
	use TestCaseTrait;

	/**
	 * PHPUnit database connection interface
	 * @var Connection $connection
	 **/
	protected $conection = null;

	/**
	 * assembles the table from the schema and provides it to PHPUnit
	 *
	 * @return QueryDataSet assembled schema for PHPUnit
	 **/
	public final function getDataSet(): QueryDataSet {
		$dataset = new QueryDataSet($this->getConnection());

		// add all the tables for the project here -- in order they were created
		$dataset->addTable("organization");
		$dataset->addTable("volunteer");
		$dataset->addTable("post");
		$dataset->addTable("favorite");
		return ($dataset);
	}

	/**
	 * templates the setUp method that runs before each test; this method expunges the database before each run
	 *
	 * @see https://phpunit.de/manual/current/en/fixtures.html#fixtures.more-setup-than-teardown PHPUnit Fixtures: setUp and tearDown
	 * @see https://github.com/sebastianbergmann/dbunit/issues/37 TRUNCATE fails on tables which have foreign key constraints
	 * @return Composite array containing delete and insert commands
	 **/
	public final function getSetUpOperation(): Composite {
		return new Composite([
			Factory::DELETE_ALL(),
			Factory::INSERT()
		]);
	}

	/**
	 * templates the tearDown method that runs after each test; this method expunges the database after each run
	 *
	 * @return Operation delete command for the database
	 **/
	public final function getTearDownOperation(): Operation {
		return (Factory::DELETE_ALL());
	}

	/**
	 * sets up the database connection and provides it to PHPUnit
	 *
	 * @see <https://phpunit.de/manual/current/en/database.html#database.configuration-of-a-phpunit-database-testcase>
	 * @return Connection PHPUnit database connection interface
	 **/
	public final function getConnection(): Connection {
		// if the connection hasn't been established, create it
		if($this->connection === null) {
			// connect to mySQL and provide the interface to PHPUnit
			$config = readConfig("/etc/apache2/capstone-mysql/feedkitty.ini");
			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/feedkitty.ini");
			$this->connection = $this->createDefaultDBConnection($pdo, $config["database"]);

		}
		return ($this->connection);
	}

	/**
	 * returns the actual PDO object; this is a convenience method
	 *
	 * @return \PDO active PDO object
	 **/
	public final function getPDO() {
		return ($this->getConnection()->getConnection());
	}
}