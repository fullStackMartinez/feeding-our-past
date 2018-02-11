<?php
namespace Edu\Cnm\FeedPast\Test;

use Edu\Cnm\FeedPast\Organization;

// load the class we are going to test
require_once (dirname(__DIR__) . "/autoload.php");

// load the uuid generator
require_once (dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Organization Class
 *
 * This is the full PHPUnit test for organization class.
 *
 * @see \Edu\Cnm\FeedPast\Organization
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class OrganizationTest extends FeedPastTest {
	/**
	 * placeholder  until account activation is created
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION;

	/**
	 * valid organization address city to use
	 * @var string $VALID_ADDRESS_CITY
	 **/
	protected $VALID_ADDRESS_CITY = "albuquerque";

	/**
	 * valid organization address state to use
	 * @var string $VALID_ADDRESS_STATE
	 **/
	protected $VALID_ADDRESS_STATE = "NM";

	/**
	 * valid organization address street to use
	 * @var string $VALID_ADDRESS_STREET
	 **/
	protected $VALID_ADDRESS_STREET = "555 San Mateo NE";

	/**
	 * valid organization address zip code to use
	 * @var string $VALID_ADDRESS_ZIP
	 **/
	protected $VALID_ADDRESS_ZIP = "87110";

	/**
	 * valid donation accepted to use
	 * @var string $VALID_DONATION_ACCEPTED
	 **/
	protected $VALID_DONATION_ACCEPTED = "yes";

	/**
	 * valid email to use
	 * @var $VALID_EMAIL
	 **/
	protected $VALID_EMAIL = "php@organization.com";

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 **/
	protected $VALID_HASH;

	/**
	 * valid operating hours to use
	 * @var $VALID_HOURS_OPEN
	 **/
	protected $VALID_HOURS_OPEN = "8 to 5";

	/**
	 * valid latitude to use
	 * @var $VALID_LAT
	 **/
	protected $VALID_LAT = "45";

	/**
	 * valid longitude to use
	 * @var $VALID_LONG
	 **/
	protected $VALID_LONG = "110";

	/**
	 * valid organization name to use
	 * @var string $VALID_NAME
	 **/
	protected $VALID_NAME = "phporganization";

	/**
	 * second valid organization name to use
	 * @var string $VALID_NAME2
	 **/
	protected $VALID_NAME2 = "phporganization2";

	/**
	 * valid phone to use
	 * @var string $VALID_PHONE
	 **/
	protected $VALID_PHONE = "+5055555555";

	/**
	 * valid salt to use
	 * $var $VALID_SALT
	 **/
	protected $VALID_SALT;

	/**
	 * valid organization url
	 * @var $VALID_URL
	 **/
	protected $VALID_URL = "www.organization.com/homepage";

	/**
	 * this will create salt and hash
	 **/
	public final function setUp() : void {
		parent::setUp();

		$password = "abc123";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));
	}

	/**
	 *this test will test inserting a valid organization profile and validate MySQL match
	 **/
	public function testInsertValidOrganization() : void {
		//get a row count, save for later use
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();

		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());

		//grab the data from MySQL and enforce the fields match our expectations
		$pdoOrganization = Organization::getOrganizationByOrganizationId($this->getPDO(), $organization->getOrganizationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}

	/**
	 * this will test creating an organizaiton profile, editing it, and then updating that organization profile
	 **/
	public function testUpdateValidOrganization() {
		//get a row count, save it for later
		$numRows = $this->getConnection()->getRowCount("organization");

		//create the new organization profile, insert into database
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);

		//edit the organization profile and update it in the database
		$organization->setOrganizationName($this->VALID_NAME2);
		$organization->update($this->getPDO());

		//grab data from database and make fields match what we are expecting
		$pdoOrganization = Organization::getOrganizationByOrganizationId($this->getPDO(), $organization->getOrganizationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}

	/**
	 * this will make a test which creates an organization profile, then deletes it
	 **/
	public function testDeleteValidOrganization() : void {
		//get row count, then save it for later
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());

		//delete the organization from the database
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$organization->delete($this->getPDO());

		//get the data from database and make organization has been deleted
		$pdoOrganization = Organization::getOrganizationByOrganizationId($this->getPDO(), $organization->getOrganizationId());
		$this->assertNull($pdoOrganization);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("organization"));
	}

	/**
	 * this is a test that inserts an organization into the database, and re-grab it from that database
	 **/
	public function testGetValidOrganizationByOrganizationId() : void{
		//get row count, save it for later
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());
		//get the data from database and check that it does what we want it to do
		$pdoOrganization = Organization::getOrganizationByOrganizationId($this->getPDO(), $organization->getOrganizationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}
	/**
	 * this is a test that will try to grab an organization that does not exist in our database
	 **/
	public function testGetInvalidOrganizationByOrganizationId() : void {
		//grab organization id that is not valid
		$invalidOrganizationId = generateUuidV4();
		$organization = Organization::getOrganizationByOrganizationId($this->getPDO(), $invalidOrganizationId);
		$this->assertNull($organization);
	}

	public function testGetValidOrganizationByOrganizationName() {
		//get row count, save for later
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());

		//grab the data from our database
		$results = Organization::getOrganizationByOrganizationName($this->getPDO(), $this->VALID_NAME);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));

		//make sure there is no overlapping of objects in organization
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Organization", $results);

		//check that our results do what we expect them to
		$pdoOrganization = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}

	/**
	 * this is a test to attempt to find an organization by its Name, that doesn't exist
	 **/
	public function testGetInvalidOrganizationByOrganizationName() : void {
		//grab organization by Name that doesn't exist in database
		$organization = Organization::getOrganizationByOrganizationName($this->getPDO(), "Organization Imposter");
		var_dump($organization);
		$this->assertCount(0, $organization);
	}

	/**
	 * this is a test that will grab an organization by their distance
	 **/
	public function testGetValidOrganizationByDistance() : void {
		//get row count, save for later
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());
		//grab data from database
		$results = Organization::getOrganizationByDistance($this->getPDO(), 111, 46, 10);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));

		//make sure there is no overlapping of objects in organization
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Organization", $results);

		//check that our results do what we expect them to
		$pdoOrganization = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}

	/**
	 * this will try to get an organization by an invalid distance
	 **/
	public function testGetInvalidOrganizationByDistance() : void {
		// grab an organization by an invalid distance
		$organization = Organization::getOrganizationByDistance($this->getPDO(), 187.4, 97.5, .0002);
		var_dump($organization);
		$this->assertCount(0, $organization);
	}

	/**
	 * this is a test to find an organization in our database by their email
	 **/
	public function testGetValidOrganizationByEmail() : void {
		//get a row count, save it
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());

		//get data from database and make sure the data matches what we want
		$pdoOrganization = Organization::getOrganizationByOrganizationEmail($this->getPDO(), $organization->getOrganizationEmail());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}
	/**
	 * this test will attempt to find an organization by an email that is invalid or does not exist
	 **/
	public function testGetInvalidOrganizationByEmail() : void {
		//get an email that is invalid or doesn't exist
		$organization = Organization::getOrganizationByOrganizationEmail($this->getPDO(), "badkitty@arlo.invalid");
		$this->assertNull($organization);
	}

	/**
	 * this is a test that gets an organization profile by activation token
	 **/
	public function testGetValidOrganizationByOrganizationActivationToken() : void {
		//get row count, save it
		$numRows = $this->getConnection()->getRowCount("organization");
		$organizationId = generateUuidV4();
		$organization = new Organization($organizationId, $this->VALID_ACTIVATION, $this->VALID_ADDRESS_CITY, $this->VALID_ADDRESS_STATE, $this->VALID_ADDRESS_STREET, $this->VALID_ADDRESS_ZIP, $this->VALID_DONATION_ACCEPTED, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HOURS_OPEN, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_NAME, $this->VALID_PHONE, $this->VALID_SALT, $this->VALID_URL);
		$organization->insert($this->getPDO());

		//get the data from database and make sure it matches our expectations
		$pdoOrganization = Organization::getOrganizationByOrganizationActivationToken($this->getPDO(), $organization->getOrganizationActivationToken());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("organization"));
		$this->assertEquals($pdoOrganization->getOrganizationId(), $organizationId);
		$this->assertEquals($pdoOrganization->getOrganizationActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoOrganization->getOrganizationAddressCity(), $this->VALID_ADDRESS_CITY);
		$this->assertEquals($pdoOrganization->getOrganizationAddressState(), $this->VALID_ADDRESS_STATE);
		$this->assertEquals($pdoOrganization->getOrganizationAddressStreet(), $this->VALID_ADDRESS_STREET);
		$this->assertEquals($pdoOrganization->getOrganizationAddressZip(), $this->VALID_ADDRESS_ZIP);
		$this->assertEquals($pdoOrganization->getOrganizationDonationAccepted(), $this->VALID_DONATION_ACCEPTED);
		$this->assertEquals($pdoOrganization->getOrganizationEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoOrganization->getOrganizationHash(), $this->VALID_HASH);
		$this->assertEquals($pdoOrganization->getOrganizationHoursOpen(), $this->VALID_HOURS_OPEN);
		$this->assertEquals($pdoOrganization->getOrganizationLatX(), $this->VALID_LAT);
		$this->assertEquals($pdoOrganization->getOrganizationLongY(), $this->VALID_LONG);
		$this->assertEquals($pdoOrganization->getOrganizationName(), $this->VALID_NAME);
		$this->assertEquals($pdoOrganization->getOrganizationPhone(), $this->VALID_PHONE);
		$this->assertEquals($pdoOrganization->getOrganizationSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoOrganization->getOrganizationUrl(), $this->VALID_URL);
	}

	/**
	 * this is a test that attempts to get an organization profile by an invalid activation token
	 **/
	public function testGetInvalidOrganizationActivationToken() : void {
		//get an activation token that is invalid or doesn't exist
		$organization = Organization::getOrganizationByOrganizationActivationToken($this->getPDO(), "6fcd8978996dc9ee36bf16c002ee6710");
		$this->assertNull($organization);
	}

}