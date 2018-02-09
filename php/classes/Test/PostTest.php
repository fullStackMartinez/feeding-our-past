<?php

namespace Edu\Cnm\FeedPast\Test;

use Edu\Cnm\FeedPast\Test;

use Edu\Cnm\FeedPast\Post;

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
	 * placeholder  until account activation is created
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION;

	/**
	 * postOrganizationId that created the post; this is for foreign key relations
	 * @var \Uuid postOrganizationId profile
	 **/
	protected $VALID_ORGANIZATION = null;


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
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));


		/**
		 * test inserting a valid Post and verify that the actual mySQL data matches
		 **/
		public
		function testInsertValidPost(): void {
			// count the number of rows and save it for later
			$numRows = $this->getConnection()->getRowCount("Post");

			// create a new Post and insert to into mySQL
			$post = generateUuidV4();
			$post = new Post($postId, $this->VALID_ORGANIZATION->getpostOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
			$post->insert($this->getPDO());

			// grab the data from mySQL and enforce the fields match our expectations
			$pdoPost = Post::getPostByPostId($this->getPDO(), $post->getPostId());
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
			$this->assertEquals($pdoPost->getPostId(), $postId);
			$this->assertEquals($pdoPost->getPostOrganizationId(), $this->VALID_ORGANIZATION;
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
			$PostId = generateUuidV4();
			$post = new Post($postId, $this->VALID_ORGANIZATION->getpostOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
			$post->insert($this->getPDO());

			// edit the Post and update it in mySQL
			$post->setPostContent($this->VALID_CONTENT2);
			$post->insert($this->getPDO());

			// grab the data from mySQL and enforce the fields match our expectations
			$pdoPost = Post::getPostByPostId($this->getPDO(), $post->getPostId());
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
			$this->assertEquals($pdoPost->getPostId(), $postId);
			$this->assertEquals($pdoPost->getPostOrganizationId(), $this->VALID_ORGANIZATION;
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
			$numRows = $this->getConnection()->getRowCount("Post");

			// create a new Post and insert to into mySQL
			$PostId = generateUuidV4();
			$post = new Post($postId, $this->VALID_ORGANIZATION->getpostOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
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
			$post = new Post($postId, $this->VALID_ORGANIZATION->getpostOrganizationId(), $this->VALID_CONTENT, $this->VALID_ENDDATETIME, $this->VALID_IMAGEURL, $this->VALID_STARTDATETIME, $this->VALID_TITLE);
			$post->insert($this->getPDO());

			// grab the data from mySQL and enforce the fields match our expectations
			$results = Post::getPostByPostOrganizationId($this->getPDO(), $post->getPostOrganizationId());
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
			$this->assertCount(1, $results);
			$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\~Test\\PostTest", $results);

			// grab the result from the array and validate it
			$pdoPost = $results[0];
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
			$this->assertEquals($pdoPost->getPostId(), $postId);
			$this->assertEquals($pdoPost->getPostOrganizationId(), $this->VALID_ORGANIZATION;
			$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT2);
			$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
			$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
			$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
			$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
		}
	}

	/**
	 * test grabbing a Post that does not exist
	 **/
	public function testGetInvalidPostByPostOrganizationId(): void {
		// grab a profile id that exceeds the maximum allowable profile id
		$post = Post::getPostByPostOrganizationId()Id($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $post);
	}

	/**
	 * test grabbing a Post by post content
	 **/
	public function testGetValidPostByPostContent(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();
		$post = new Post($postId, $this->profile->getPostOrganizationId(), $this->VALID_CONTENT, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Post::getPostByPostContent($this->getPDO(), $post->getPostContent());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertCount(1, $results);

		// ensure no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\~Test\\FeedPastTest", $results);

		// grab the result from the array and validate it
		$pdoPost = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $postId);
		$this->assertEquals($pdoPost->getPostOrganizationId(), $this->VALID_ORGANIZATION;
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT1);
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT2);
		$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
		$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
		$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
	}

	/**
	 * test grabbing Post by content that does not exist
	 **/
	public function testGetInvalidPostByPostContent(): void {
		// grab a post by content that does not exist
		$post = Post::getPostByPostContent($this->getPDO(), "Rhinos make great pets!");
		$this->assertCount(0, $post);
	}


	/**
	 * test grabbing all Posts
	 **/
	public function testGetAllValidPosts(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("post");

		// create a new Post and insert to into mySQL
		$postId = generateUuidV4();
		$post = new Post($postId, $this->organization->getPostOrganizationId(), $this->VALID_CONTENT, $this->VALID_TITLE);
		$post->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Post::getAllPosts($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\~Test\\PostTest", $results);

		// grab the result from the array and validate it
		$pdoPost = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("post"));
		$this->assertEquals($pdoPost->getPostId(), $postId);
		$this->assertEquals($pdoPost->getPostOrganizationId(), $this->VALID_ORGANIZATION;
		$this->assertEquals($pdoPost->getPostContent(), $this->VALID_CONTENT2);
		$this->assertEquals($pdoPost->getPostEndDateTime(), $this->VALID_ENDDATETIME);
		$this->assertEquals($pdoPost->getPostImageUrl(), $this->VALID_IMAGEURL);
		$this->assertEquals($pdoPost->getPostStartDateTime(), $this->VALID_STARTDATETIME);
		$this->assertEquals($pdoPost->getPostTitle(), $this->VALID_TITLE);
	}
}


