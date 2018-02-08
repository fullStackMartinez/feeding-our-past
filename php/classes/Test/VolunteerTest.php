<?php
use Edu\Cnm\FeedPast\Test;

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
class VolunteerTest extends FeedPastTest {
	/**
	 * placeholder until account activation is created
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION = null;

	/**
	 * valid availability to use
	 * @var string $VALID_AVAILABILITY
	 **/
	protected $VALID_AVAILABILITY = "M/W/F evenings and some Saturdays";

	/**
	 * valid email to use
	 * @var string $VALID_EMAIL
	 **/
	protected $VALID_EMAIL = "volunteer@unittest.php";

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 **/
	protected $VALID_HASH = null;

	/**
	 * valid name to use
	 * @var string $VALID_NAME
	 **/
	protected $VALID_NAME = "Captain Arlo";

	/**
	 * second valid name to use
	 * @var string $VALID_NAME2
	 **/
	protected $VALID_NAME2 = "Kitty McKitty";

	/**
	 * valid phone number to use
	 * @var string $VALID_PHONE
	 **/
	protected $VALID_PHONE = "(555) 867-5309";

	/**
	 * valid salt to use to create the volunteer object to own the test
	 * @var string $VALID_SALT
	 **/
	protected $VALID_SALT = null;

	/**
	 * run the default setup operation to create salt and hash
	 **/
	public final function setUp() : void {

		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));
	}

	/**
	 * test inserting a valid Volunteer and verify that the actual mySQL data matches
	 **/
	public function testInsertValidVolunteer() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("volunteer");

		$volunteerId = generateUuidV4();

		$volunteer = new Volunteer($volunteerId, $this->VALID_ACTIVATION, $this->VALID_AVAILABILITY, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT);
		$volunteer->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVolunteer = Volunteer::getVolunteerByVolunteerId($this->getPDO(), $volunteer->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));
		$this->assertEquals($pdoVolunteer->getVolunteerId(), $volunteerId);
		$this->assertEquals($pdoVolunteer->getVolunteerActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoVolunteer->getVolunteerAvailability(), $this->VALID_AVAILABILITY);
		$this->assertEquals($pdoVolunteer->getVolunteerEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoVolunteer->getVolunteerHash(), $this->VALID_HASH);
		$this->assertEquals($pdoVolunteer->getVolunteerName(), $this->VALID_NAME);
		$this->assertEquals($pdoVolunteer->getVolunteerPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoVolunteer->getVolunteerSalt(), $this->VALID_SALT);
	}

	/**
	 * test inserting a Volunteer, editing it, and then updating it
	 **/
	public function testUpdateValidVolunteer() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("volunteer");

		// create a new Volunteer and insert into mySQL
		$volunteerId = generateUuidV4();
		$volunteer = new Volunteer($volunteerId, $this->VALID_ACTIVATION, $this->VALID_AVAILABILITY, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT);
		$volunteer->insert($this->getPDO());

		// edit the Volunteer and update it in mySQL
		$volunteer->setVolunteerName($this->VALID_NAME2);
		$volunteer->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVolunteer = Volunteer::getVolunteerByVolunteerId($this->getPDO(), $volunteer->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));
		$this->assertEquals($pdoVolunteer->getVolunteerId(), $volunteerId);
		$this->assertEquals($pdoVolunteer->getVolunteerActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoVolunteer->getVolunteerAvailability(), $this->VALID_AVAILABILITY);
		$this->assertEquals($pdoVolunteer->getVolunteerEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoVolunteer->getVolunteerHash(), $this->VALID_HASH);
		$this->assertEquals($pdoVolunteer->getVolunteerName(), $this->VALID_NAME2);
		$this->assertEquals($pdoVolunteer->getVolunteerPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoVolunteer->getVolunteerSalt(), $this->VALID_SALT);
	}

	/**
	 * test creating a Volunteer and then deleting it
	 **/
	public function testDeleteValidVolunteer() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("volunteer");

		// create a new Volunteer and insert into mySQL
		$volunteerId = generateUuidV4();
		$volunteer = new Volunteer($volunteerId, $this->VALID_ACTIVATION, $this->VALID_AVAILABILITY, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT);
		$volunteer->insert($this->getPDO());

		// delete the Volunteer from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));
		$volunteer->delete($this->getPDO());

		// grab the data from mySQL and enforce the Volunteer does not exist
		$pdoVolunteer = Volunteer::getVolunteerByVolunteerId($this->getPDO(), $volunteer->getVolunteerId());
		$this->assertNull($pdoVolunteer);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("volunteer"));
	}

	/**
	 * test inserting a Volunteer and regrabbing it from mySQL
	 **/
	public function testGetValidVolunteerByVolunteerId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("volunteer");

		// create a new Volunteer and insert into mySQL
		$volunteerId = generateUuidV4();
		$volunteer = new Volunteer($volunteerId, $this->VALID_ACTIVATION, $this->VALID_AVAILABILITY, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT);
		$volunteer->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVolunteer = Volunteer::getVolunteerByVolunteerId($this->getPDO(), $volunteer->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));
		$this->assertEquals($pdoVolunteer->getVolunteerId(), $volunteerId);
		$this->assertEquals($pdoVolunteer->getVolunteerActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoVolunteer->getVolunteerAvailability(), $this->VALID_AVAILABILITY);
		$this->assertEquals($pdoVolunteer->getVolunteerEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoVolunteer->getVolunteerHash(), $this->VALID_HASH);
		$this->assertEquals($pdoVolunteer->getVolunteerName(), $this->VALID_NAME);
		$this->assertEquals($pdoVolunteer->getVolunteerPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoVolunteer->getVolunteerSalt(), $this->VALID_SALT);
	}

	/**
	 * test grabbing a Volunteer that does not exist
	 **/
	public function testGetInvalidVolunteerByVolunteerId() : void {
		// grab a volunteer id that exceeds the maximum allowable volunteer id
		$fakeVolunteerId = generateUuidV4();
		$volunteer = Volunteer::getVolunteerByVolunteerId($this->getPDO(), $fakeVolunteerId);
		$this->assertNull($volunteer);
	}

	/**
	 * test grabbing a Volunteer by name
	 **/
	public function testGetValidVolunteerByName() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("volunteer");

		// create a new Volunteer and insert into mySQL
		$volunteerId = generateUuidV4();
		$volunteer = new Volunteer($volunteerId, $this->VALID_ACTIVATION, $this->VALID_AVAILABILITY, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT);
		$volunteer->insert($this->getPDO());

		// grab the data from mySQL
		$results = Volunteer::getVolunteerByVolunteerName($this->getPDO(), $this->VALID_NAME);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));

		// enforce no other objects are bleeding into volunteer
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Volunteer");

		// enforce the results meet expectations
		$pdoVolunteer = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));
		$this->assertEquals($pdoVolunteer->getVolunteerId(), $volunteerId);
		$this->assertEquals($pdoVolunteer->getVolunteerActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoVolunteer->getVolunteerAvailability(), $this->VALID_AVAILABILITY);
		$this->assertEquals($pdoVolunteer->getVolunteerEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoVolunteer->getVolunteerHash(), $this->VALID_HASH);
		$this->assertEquals($pdoVolunteer->getVolunteerName(), $this->VALID_NAME);
		$this->assertEquals($pdoVolunteer->getVolunteerPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoVolunteer->getVolunteerSalt(), $this->VALID_SALT);
	}

	/**
	 * test grabbing a Volunteer by a name that does not exist
	 **/
	public function testGetInvalidVolunteerByName() : void {
		// grab a name that does not exist
		$volunteer = Volunteer::getVolunteerByVolunteerName($this->getPDO(), "flash");
		var_dump($volunteer);
		$this->assertCount(0, $volunteer);
	}

	/**
	 * test grabbing a Volunteer by email
	 **/
	public function testGetValidVolunteerByEmail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("volunteer");

		// create a new Volunteer and insert into mySQL
		$volunteerId = generateUuidV4();
		$volunteer = new Volunteer($volunteerId, $this->VALID_ACTIVATION, $this->VALID_AVAILABILITY, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT);
		$volunteer->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoVolunteer = Volunteer::getVolunteerByVolunteerEmail($this->getPDO(), $volunteer->getVolunteerEmail());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("volunteer"));
		$this->assertEquals($pdoVolunteer->getVolunteerId(), $volunteerId);
		$this->assertEquals($pdoVolunteer->getVolunteerActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoVolunteer->getVolunteerAvailability(), $this->VALID_AVAILABILITY);
		$this->assertEquals($pdoVolunteer->getVolunteerEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoVolunteer->getVolunteerHash(), $this->VALID_HASH);
		$this->assertEquals($pdoVolunteer->getVolunteerName(), $this->VALID_NAME);
		$this->assertEquals($pdoVolunteer->getVolunteerPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoVolunteer->getVolunteerSalt(), $this->VALID_SALT);
	}

}