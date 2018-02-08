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

		$password = "efg456";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("ego123", $password, $this->VALID_SALT, 373255);
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
	}
}