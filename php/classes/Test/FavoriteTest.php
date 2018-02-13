<?php

namespace Edu\Cnm\FeedPast\Test;

use Edu\Cnm\FeedPast\{Favorite, Volunteer, Post, Organization};

// grab the class under scrunity
require_once(dirname(__DIR__, 1) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PhpUnit test for the favorite class.
 *
 * It is complete because ALL mySQL/PDO enabled methods
 * are tested for both valid and invalid inputs.
 *
 * @see Favorite
 * @author Jeffrey Brink <jeffreybrink@gmx.com
 * @author Dylan McDonald <dmcdonald@cnm.edu>
 **/
class FavoriteTest extends FeedPastTest {

	/**
	 * Post that was favorited, this is for foriegn Key relations
	 * @var Post $post
	 **/
	protected $post;

	/**
	 * Volunteer that created the Favorite, this is
	 * for the foriegn Key relations
	 * @var Volunteer $volunteer
	 **/
	protected $volunteer;

	/**
	 * Organization that created the post
	 * $var Organization $organization
	 **/
	protected $organization;

	/**
	 * valid hash to use
	 * @var $valid_Hash
	 **/
	protected $VALID_HASH;


	/**
	 * valid salt to use to create the post object to own the text
	 * @var string $VALID_SALT
	 **/
	protected $VALID_SALT;


	/**
	 * valid activationToken to create the post object to own the test
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION;

	/**
	 * Valid timestamp to use as sunriseTweetDate
	 */
	protected $VALID_SUNRISEDATE = null;
	/**
	 * Valid timestamp to use as sunsetFavoriteDate
	 */
	protected $VALID_SUNSETDATE = null;

	/**
	 * create dependant objects before running each test
	 **/
	public final function setup(): void {
		// run the default setup() method first
		parent::setUp();

		// create a salt and hash for the mocked post
		$password = "abbadabba";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		$this->VALID_SUNRISEDATE = new \DateTime();
		$this->VALID_SUNSETDATE = new \DateTime();

		//creat and insert a mocked organization
		$this->organization = new Organization(generateUuidV4(), $this->VALID_ACTIVATION, "albuquerque", "NM", "555 San Mateo NE", "87110", "yes", "php@organization.com", $this->VALID_HASH, "8 to 5", "45", "110", "phporganization", "+5055555555", $this->VALID_SALT, null);
		$this->organization->insert($this->getPDO());

		// create and insert the mocked post
		$this->post = new Post(generateUuidV4(), $this->organization->getOrganizationId(), "some post content", $this->VALID_SUNSETDATE, null, $this->VALID_SUNRISEDATE, "Food drive coming up");
		$this->post->insert($this->getPDO());

		//create and insert the mocked volunteer
		$this->volunteer = new Volunteer(generateUuidV4(), null, "Fridays at 5pm", 	"phillyonfire@burn.com", $this->VALID_HASH, "Ted Random", "719-367-9856", $this->VALID_SALT);
		$this->volunteer->insert($this->getPDO());

	}

	/**
	 * test inserting a valid favorite and verifying that the actual mySQL data matches
	 **/

	public function testInsertValidFavorite(): void {
		// count the numbers of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->favorite->getPostId(), $this->volunteer->getVolunteerId());
		$favorite->insert($this->getPDO());

	// grab the data from mySQL and enforce the fields match our expectations
$pdoFavorite = Favorite::testInsertValidFavorite($this->getPDO(), $this->post->getPostId(), $this->volunteer
->getVolunteerId());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
}

	/**
	 * test creating a favorite and the deleting it
	 **/
	public function testDeleteValidFavorite(): void {
		// count the rows and save for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId());
		$favorite->insert($this->getPDO());

		// delete the Favorite from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$favorite->delete($this->getPDO());

		// grab the data from mySQL and enforce the Volunteer does not exist
		$pdoFavorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(), $this->post->getPostId(), $this->volunteer->getVolunteerId());
		$this->assertNull($pdoFavorite);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("favorite"));
	}

	/**
	 * test inserting a Favorite and regrabbing it from mySQL
	 **/
	public function testGetValidFavoriteByPostIdAndVolunteerId(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId());
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoFavorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(), $this->post->getPostId(), $this->volunteer
			->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
		$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
	}


	/**
	 * test grabbing a Favorite that does not exist
	 **/
	public function testGetInvalidFavoriteByPostIdAndVolunteerId() {
		// grab a volunteerId and postId that exceeds the maximum allowable volunteerId and postId
		$favorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(), generateUuidV4(), generateUuidV4());
		$this->assertNull($favorite);
	}

	/**
	 * test grabbing a Favorite by volunteerid
	 **/
	public function testGetValidFavoriteByVolunteerId(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");
		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId());
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Favorite::getFavoriteByFavoriteVolunteerId($this->getPDO(), $this->volunteer->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Favorite", $results);


		// grab the result from the array and validate it
		$pdoFavorite = $results[0];
		$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
		$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
	}


	/**
	 * test grabbing a Favorite by a volunteerId that does not exist
	 **/
	public function testGetInvalidFavoriteByVolunteerId(): void {
		// grab a Volunteer id that exceeds the maximum allowable volunteer id
		$favorite = Favorite::getFavoriteByFavoriteVolunteerId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $favorite);
	}


	/**
	 * test grabbing a Favorite by post id
	 **/

	public function testGetValidFavoriteByPostId(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");
		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId());
		$favorite->insert($this->getPDO());


		// grab the data from mySQL and enforce the fields match our expectations
		$results = Favorite::getFavoriteByFavoritePostId($this->getPDO(), $this->post->getPostId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Favorite", $results);


		// grab the result from the array and validate it
		$pdoFavorite = $results[0];
		$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
		$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
	}


	/**
	 *  * test grabbing a Favorite by a Post id that does not exist
	 **/

	public function testGetInvalidFavoriteByPostId(): void {
		// grab a volunteer id that exceeds the maximum allowable post id
		$favorite = Favorite::getFavoriteByFavoritePostId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $favorite);
	}
}



