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
	protected $VALID_EMAIL = "php@phpunit.com";

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 **/
	protected $VALID_HASH;

	/**
	 *
	 */

	/**
	 * valid organization name to use
	 * @var string $VALID_NAME
	 **/
	protected $VALID_NAME = "@phporganization";

	/**
	 * second valid organization name to use
	 * @var string $VALID_NAME2
	 **/
	protected $VALID_NAME2 = "@passedtest";

	/**
	 * valid
	 */
}