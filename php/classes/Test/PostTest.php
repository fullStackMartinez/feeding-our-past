<?php
// My name is Peter Street
namespace Edu\Cnm\FeedPast\Test;

use Edu\Cnm\FeedPast\Test;

use Edu\Cnm\FeedPast\{Post, Organization};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Post class
 *
 * This is a complete PHPUnit test of the Post class.
 * It is complete because ALL mySQL/PDO enabled methods are tested for both invalid and valid inputs.
 *
 * @see Post
 * @author Peter Street <peterbstreet@gmail.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class PostTest extends FeedPastTest {
	/**
	 * Organization the created the post
	 * This is the variable that sets the parent child
	 * @var Organization organization
	 **/
	protected $organization = null;

	/**
	 * placeholder  until account activation is created
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION;

	/**
	 * postOrganizationId that created the post; this is for foreign key relations
	 * @var \Uuid postOrganizationId profile
	 * //   protected $VALID_ORGANIZATION = "Donation Center";
	 **/

	/**
	 * Valid post Content
	 * @var string $VALID_CONTENT
	 */
	protected $VALID_CONTENT = "Valid Content";

	/**
	 * Valid post Content
	 * @var string $VALID_CONTENT
	 */
	protected $VALID_CONTENT2 = "Valid Content2";

	/**
	 * Valid post EndDateTime
	 * This starts as null and is assigned later
	 * @var \DateTime $VALID_EndDateTime
	 */
	protected $VALID_ENDDATETIME = null;

	/**
	 * Valid post image url
	 * @var string $VALID_IMAGEURL
	 **/
	protected $VALID_IMAGEURL = "www.ValidImage.moc";

	/**
	 * Valid post StartDateTime
	 * This starts as null and is assigned later
	 * @var \DateTime $VALID_STARTDATETIME
	 **/
	protected $VALID_STARTDATETIME = null;

	/**
	 * Valid post Title
	 * @var string $VALID_TITLE
	 **/
	protected $VALID_TITLE = "Valid Title";

	/**
	 * HMM Do I need this??????
	 * create dependent objects before running each test
	 **/
	public final function setUp(): void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		// create and insert the mocked profile
		$this->organization = new Organization(generateUuidV4(), $this->VALID_ACTIVATION, "albuquerque", "NM", "555 San Mateo NE", "87110", "yes", "php@organization.com", $this->VALID_HASH, "8 to 5", "45", "110", "phporganization", "+5055555555", $this->VALID_SALT, null);
		$this->organization->insert($this->getPDO());
		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_STARTDATETIME = new \DateTime();
		$this->VALID_ENDDATETIME = new \DateTime();
	}


	/**
	 * test inserting a valid Post and verify that the actual mySQL data matches
	 **/
	public function testInsertValidPost(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();

		$post = new Post($postId, $this->organization->getOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPost = Post::getPostByPostId($this->getPDO(), $post->getPostId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $postId);
		$this->assertEquals($pdoPost->getPostOrganizationId(), $this->organization->getOrganizationId());
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT);
		$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
		$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
		$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
	}

	/**
	 * test inserting a Post, editing it, and then updating it
	 **/
	public
	function testUpdateValidPost(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();
		$post = new Post($postId, $this->organization->getOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// edit the Post and update it in mySQL
		$post->setPostContent($this->VALID_CONTENT2);
		$post->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPost = Post::getPostByPostId($this->getPDO(), $post->getPostId());
		$this->assertEquals($pdoPost->getPostId(), $postId);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));

		$this->assertEquals($pdoPost->getPostOrganizationId(), $this->organization->getOrganizationId());
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT2);
		$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
		$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
		$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
	}


	/**
	 * test creating a Post and then deleting it
	 **/
	public
	function testDeleteValidPost(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();
		$post = new Post($postId, $this->organization->getOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// delete the Post from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$post->delete($this->getPDO());

		// grab the data from mySQL and enforce the Post does not exist
		$pdoPost = Post::getPostByPostId($this->getPDO(), $post->getPostId());
		$this->assertNull($pdoPost);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("post"));
	}


	/**
	 * test grabbing a Post that does not exist
	 **/
	public
	function testGetInvalidPostByPostId(): void {
		// grab a profile id that exceeds the maximum allowable profile id
		$post = Post::getPostByPostId($this->getPDO(), generateUuidV4());
		$this->assertNull($post);
	}

	/**
	 * test inserting a Post and regrabbing it from mySQL
	 **/
	public
	function testGetValidPostByPostOrganizationId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();
		$post = new Post($postId, $this->organization->getOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Post::getPostByPostOrganizationId($this->getPDO(), $post->getPostOrganizationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Post", $results);

		// grab the result from the array and validate it
		$pdoPost = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $postId);
		$this->assertEquals($pdoPost->getPostOrganizationId(), $this->organization->getOrganizationId());
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT);
		$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
		$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
		$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
	}

	/**
	 * test inserting a Post and regrabbing it from mySQL
	 **/
	public function testGetValidPostByPostEndDateTime(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();
		$post = new Post($postId, $this->organization->getOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Post::getPostByPostEndDateTime($this->getPDO(), $this->VALID_ENDDATETIME->getTimestamp());
//$post->getPostEndDateTime());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\FeedPast\\Post", $results);

		// grab the result from the array and validate it
		$pdoPost = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $postId);
		$this->assertEquals($pdoPost->getPostOrganizationId(), $this->organization->getOrganizationId());
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT);
		$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
		$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
		$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
	}

	/**
	 * test grabbing a Post that does not exist
	 **/
	public function testGetInvalidPostByPostOrganizationId(): void {
		// grab a profile id that exceeds the maximum allowable profile id
		$post = Post::getPostByPostOrganizationId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $post);
	}
}

