<?php
/**
 * Created by PhpStorm.
 * User: petersdata
 * Date: 2/5/18
 * Time: 2:39 PM
 *    @author Peter B Street <peterbstreet@gmail.com> - Code Revision
 *    @author Dylan McDonald <dmcdonald21@cnm.edu> - Core code outline and format
 *    @author php-fig  <http://www.php-fig.org/psr/> - PHP Standards Recommendation
 *    @author Ramsey - Uuid toolset
**/

/**
 * Set the namespace for object FeedingOurPast
 *
 *    namespace must come before autoload
 *    namespaces and autoload names must match
 *    Class Name and Namespace are PSR4
**/
namespace Edu\Cnm\FeedPast;
require_once("autoload.php");

/**
 * Path to autoload.php
 *
 *    The __DIR__, 2 indicates that the autoload will go up 0, 1, 2 directory layers starting with the current directory layer to load autoload.
 *    Do we need to declare side effect?
**/
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

/**
 * Here we load Ramsey's Uuid toolset
*/

use Edu\Cnm\FeedinPast\ValidateUuid;
use Ramsey\Uuid\Uuid;

/**
 * The class is definded and set to Post
 * This class uses Uuids
 *
 *    This object is based on the post table in feeding-our-past.sql
 *    Class Name and Namespace are PSR4
 *    "implements \JsonSerializable" removed until later
**/
class Post implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * Post uses postId as the primary key
	 * postId is the primary key
	 * postId is the post's unique id
	 * @var string $postId
	 * postId state set to private
	 */
	private $postId;
	use ValidateUuid;
	/**
	 * Post uses postOrganizationId as the foreign key
	 * postOrganizationId is the foreign key
	 * postOrganizationId is the posting organizations unique id
	 * @var string postOrganizationId
	 * postOrganizationId state set to private
	 */
	private $postOrganizationId;
	use ValidateUuid;
	/**
	 * Post uses postContent as an element
	 * This is the content of the post
	 * @var string postContent
	 * postContent state set to private
	 */
	private $postContent;

	/**
	 * Post uses postEndDateTime as an element
	 * This is the date that the post is to be removed from service
	 * @var string postEndDateTime
	 * postEndDateTime state set to private
	 */
	private $postEndDateTime;
	use ValidateDate

/**
 * Post uses postImageUrl as an element
 * This is the url of the image associated with the content
 * @var string postImageUrl
 * postImageUrl state set to private
 */
private $postImageUrl;

/**
 * Post uses postStartDateTime as an element
 * This is the start date and time of the post
 * @var string postStartDateTime
 * postStartDateTime state set to private
 */
private $postStartDateTime;
	use ValidateDate

/**
 * Post uses postTitle as an element
 * This is the title of the post
 * @var string postTitle
 * postTitle state set to private
 */
private $postTitle;

/**
 * constructor for Post
 *
 * Constructs the object post and associated object's states
 * @param string $newPostId is the poster's unique and required id
 * @param string $newPostOrganizationId is the posting organization's unique and required id
 * @param string $newPostContent is the content of the post
 * @param string $newPostEndDateTime is the required date and time the post may be removed
 * @param string $newPostImageUrl is the location of the image that may accompany the post
 * @param string $newPostStartDateTime is the required date and time the post may be added
 * @param string $newPostTitle is the title of the post
 * @throws \InvalidArgumentException if data types are not valid
 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
 * @throws \TypeError if data types violate type hints
 * @throws \Exception if some other exception occurs
 * @Documentation <https://php.net/manual/en/language.oop5.decon.php>
 * @throws & @Documentation notes are straight from Dylan McDonald's code template
 * Exceptions code is straight from Dylan McDonald's code
 */
	public function __construct($newPostId, $newPostOrganizationId, $newPostContent, $newPostEndDateTime, $newPostImageUrl, $newPostStartDateTime, $newPostTitle) {
		try {
			$this->setPostId($newPostId);
			$this->setPostOrganizationId($newPostOrganizationId);
			$this->setPostContent($newPostContent);
			$this->setPostEndDateTime($newPostEndDateTime);
			$this->setPostImageUrl($newPostImageUrl);
			$this->setPostStartDateTime($newPostStartDateTime);
			$this->setPostTitle($newPostTitle);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

/**
 * accessor method for postId
 * @return string value of postId
 */
	public function getPostId(): Uuid {
		return ($this->postId);
	}

/**
 * mutator method for postId
 * @param string $newPostId new value for postId
 * @throws \RangeException if $newPostId is not positive
 * @throws \TypeError if $newPostId is not a uuid or string
 */
	public function setPostId($newPostId): void {
		try {
			$uuid = self::validateUuid($newPostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		/**
		 * Convert and store the postId
		 */
		$this->postId = $uuid;
	}

/**
 * accessor method for postOrganizationId
 * @return string value of postOrganizationId
 **/
	public function getPostOrganizationId(): Uuid {
		return ($this->postOrganizationId);
	}

/**
 * mutator method for postOrganizationId
 * @param string $newPostOrganizationId
 * @throws \RangeException if $newPostOrganizationId is not positive
 * @throws \TypeError if $newUserId is not a string or uuid
 **/
	public function setpostOrganizationId($newPostOrganizationId): void {
		try {
			$uuid = self::validateUuid($newPostOrganizationId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		/*
		 * Convert and store the postOrganizationId
		*/
		$this->postOrganizationId = $uuid;
	}

/**
 * accessor method for postContent
 * @return string value of postContent
 */
	public function getPostContent(): string {
		return ($this->postContent);
	}

/**
 * mutator method for postContent
 * @param string $newPostContent is the new post content
 * @throws \InvalidArgumentException if $newPostContent is not a string or insecure
 * @throws \RangeException if $newPostContent is > 4096 characters
 * @throws \TypeError if $newPostContent is not a string
 */
	public function setPostContent(string $newPostContent): void {
		$newPostContent = trim($newPostContent);
		$newPostContent = filter_var($newPostContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostContent) === true) {
			throw(new \InvalidArgumentException("Post Content is empty"));
		}
		if(strlen($newPostContent) > 4096) {
			throw(new \RangeException("Post Content too Large"));
		}
		/**
		 * store the post content
		 */
		$this->postContent = $newPostContent;
	}


	/**
	 * accessor method for postEndDateTime
	 * @return \DateTime value of postEndDateTime
	 */
	public function getPostEndDateTime() : \DateTime {
		return($this->postEndDateTime);
	}

	/**
	 * mutator method for postEndDateTime
	 * @param \DateTime|string $newPostEndDateTime is the time that the post may be removed
	 * @param \DateTime|string $newPostEndDateTime date to validate
	 * @throws \InvalidArgumentException if $newPostEndDateTime is a date that does not exist
	 * @throws \InvalidArgumentException if the date is in an invalid format
	 * @throws \RangeException if the date is a date that does not exist
	 **/
	public function setPostEndDateTime($newPostEndDateTime = null): void {
		if($newPostEndDateTime === null) {
		$this->postEndDateTime = new \DateTime();
		return;
		}

		try {
			$newPostEndDateTime = self::validateDateTime($newPostEndDateTime);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->postEndDateTime = $newPostEndDateTime;
	}


/**
 * accessor method for postImageUrl
 * @return string value of postImageUrl
 */
public function getPostImageUrl() : void {
	return ($this->postImageUrl);
}
/**
 * mutator method for postImageUrl
 * @param string $newPostImageUrl is the url of the image added to a post
 **/
public function setPostImageUrl($newPostImageUrl) : void {


$this->postImageUrl = $newPostImageUrl;
}


/**
 * accessor method for postStartDateTime
 * @return \DateTime string value of postStartDateTime
 */
public function getPostStartDateTime() : \DateTime {
	return($this->postStartDateTime);
}

/**
 * mutator method for postStartDateTime
 * @param \DateTime|string $newPostStartDateTime is the time that the post may be removed
 * @param \DateTime|string $newPostStartDateTime date to validate
 * @throws \InvalidArgumentException if $newPostStartDateTime is a date that does not exist
 * @throws \InvalidArgumentException if the date is in an invalid format
 * @throws \RangeException if the date is a date that does not exist
 **/
public function setPostStartDateTime($newPostStartDateTime = null) : void {
	if($newPostStartDateTime === null) {
	$this->postStartDateTime = new \DateTime();
	return;
	}

			try {
				$newPostStartDateTime = self::validateDateTime($newPostStartDateTime);
			} catch(\InvalidArgumentException | \RangeException $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}
		$this->postStartDateTime = $newPostStartDateTime;
}

/**
 * accessor method for postTitle
 * @return string value of postTitle
 */
public function getPostTitle() : void {
	return($this->postTitle);
}
/**
 * mutator method for postTitle
 * @param string $newPostTitle is the title of the post
 **/
public function setPostTitle($newPostTitle) : void {


	$this->postTitle = $newPostTitle;
}

/**
	 * inserts this post into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO post(postId, postOrganizationId, postContent, postEndDateTime, postImageUrl, postStartDateTime, postTitle) VALUES(:postId, :postOrganizationId, :postContent, :postEndDateTime, :postImageUrl, :postStartDateTime, :postTitle)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->postEndDateTime->format("Y-m-d H:i:s.u");
		$formattedDate = $this->postStartDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["postId" => $this->postId->getBytes(), "postOrganizationId" => $this->postOrganizationId->getBytes(), "postContent" => $this->postContent, "postEndDateTime" => $formattedDate, "postImageUrl" => $this->postImageUrl, "postStartDateTime" => $formattedDate, "postTitle" => $this->postTitle];
		$statement->execute($parameters);
	}


	/**
	 * deletes this post from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM post WHERE postId = :postId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["postId" => $this->postId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this post in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE post SET postOrganizationId = :postOrganizationId, postContent = :postContent, postEndDateTime = :postEndDateTime, postImageUrl = :postImageUrl, postStartDateTime = :postStartDateTime, postTitle = :postTitle WHERE postId = :postId";
		$statement = $pdo->prepare($query);

		$formattedDate = $this->postEndDateTime->format("Y-m-d H:i:s.u");
		$formattedDate = $this->postStartDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["postId" => $this->postId->getBytes(), "postOrganizationId" => $this->postOrganizationId->getBytes(), "postContent" => $this->postContent, "postEndDateTime" => $formattedDate, "postImageUrl" => $this->postImageUrl, "postStartDateTime" => $formattedDate, "postTitle" => $this->postTitle];
		$statement->execute($parameters);
	}
	/**
	 * gets the post by postId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postId post id to search for
	 * @return post|null post found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getPostByPostId(\PDO $pdo, $postId) : ?post {
		// sanitize the postId before searching
		try {
			$postId = self::validateUuid($postId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT postId, postOrganizationId, postContent, postEndDateTime, postImageUrl, postStartDateTime, postTitle FROM post WHERE postId = :postId";
		$statement = $pdo->prepare($query);
		// bind the post id to the place holder in the template
		$parameters = ["postId" => $postId->getBytes()];
		$statement->execute($parameters);

		// grab the post from mySQL
		try {
			$post = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$post = new Post($row["postId"], $row["postOrganizationId"], $row["postContent"], $row["postEndDateTime"], $row["postImageUrl"], $row["postStartDateTime"], $row["postTitle"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($post);
	}

	/**
	 * gets the post by postOrganizationId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param  string $postOrganizationId to search by
	 * @return \SplFixedArray SplFixedArray of posts found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getPostByPostOrganizationId(\PDO $pdo, $postOrganizationId) : \SplFixedArray {

		try {
			$postOrganizationId = self::validateUuid($postOrganizationId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT postId, postOrganizationId, postContent, postEndDateTime, postImageUrl, postStartDateTime, postTitle FROM post WHERE postOrganizationId = :postOrganiationId";
		$statement = $pdo->prepare($query);
		// bind the user id to the place holder in the template
		$parameters = ["postOrganizationId" => $postOrganizationId->getBytes()];
		$statement->execute($parameters);
		// build an array of posts
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postOrganizationId"], $row["postContent"], $row["postEndDateTime"], $row["postImageUrl"], $row["postStartDateTime"], $row["postTitle"]);
				[$post->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($posts);
	}

	/**
	 * gets the posts by postEndDateTime
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postEndDateTime post content to search for
	 * @return \SplFixedArray SplFixedArray of posts found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getpostByEndDateTime(\PDO $pdo, $postEndDateTime) : \SplFixedArray {
		{
			// create query template
			$query = "SELECT postId, postOrganizationID, postContent, postEndDateTime, postImageUrl, postStartDateTime, postTitle FROM post WHERE postEndDateTime = :postEndDateTime";
			$statement = $pdo->prepare($query);
			// bind the content to the place holder in the template
			$postEndDateTime = "%$postEndDateTime%";
			$parameters = ["postEndDateTime" => $postEndDateTime];
			$statement->execute($parameters);

			// build an array of posts
			$posts = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$posts = new Post($row["postId"], $row["postOrganizationId"], $row["postContent"], $row["postEndDateTime"], $row["postImageUrl"], $row["postStartDateTime"], $row["postTitle"]);
					$posts[$posts->key()] = $posts;
					$posts->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
		}
		return($posts);}

		/**
		 * gets the posts by postStartDateTime
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $postStartDateTime post content to search for
		 * @return \SplFixedArray SplFixedArray of posts found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static function getpostByStartDateTime(\PDO $pdo, $postStartDateTime) : \SplFixedArray {
			{
				// create query template
				$query = "SELECT postId, postOrganizationID, postContent, postEndDateTime, postImageUrl, postStartDateTime, postTitle FROM post WHERE postEndDateTime = :postEndDateTime";
				$statement = $pdo->prepare($query);
				// bind the content to the place holder in the template
				$postStartDateTime = "%$postStartDateTime%";
				$parameters = ["postStartDateTime" => $postStartDateTime];
				$statement->execute($parameters);

				// build an array of posts
				$posts = new \SplFixedArray($statement->rowCount());
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				while(($row = $statement->fetch()) !== false) {
					try {
						$posts = new Post($row["postId"], $row["postOrganizationId"], $row["postContent"], $row["postEndDateTime"], $row["postImageUrl"], $row["postStartDateTime"], $row["postTitle"]);
						$posts[$posts->key()] = $posts;
						$posts->next();
					} catch(\Exception $exception) {
						// if the row couldn't be converted, rethrow it
						throw(new \PDOException($exception->getMessage(), 0, $exception));
					}
				}
			}
			return($posts);}

			/**
		 * gets all posts
		 *
		 * @param \PDO $pdo PDO connection object
		 * @return \SplFixedArray SplFixedArray of posts found or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static__function getAllposts(\PDO $pdo) : \SPLFixedArray {
	// create query template
$query = "SELECT postId, postOrganizationId, postContent, postEndDateTime, postImageUrl, postStartDateTime, postTitle FROM post";
$statement = $pdo->prepare($query);
$statement->execute();

	// build an array of posts
$posts = new \SplFixedArray($statement->rowCount());
$statement->setFetchMode(\PDO::FETCH_ASSOC);
while(($row = $statement->fetch()) !== false) {
try {
$post = new post($row["postId"], $row["postOrganizationId"], $row["postContent"], $row["postEndDateTime"], $row["postImageUrl"], $row["postStartDateTime"], $row["postTitle"]);
$posts[$posts->key()] = $post;
$posts->next();
}

catch
(\Exception $exception) {
	// if the row couldn't be converted, rethrow it
	throw(new \PDOException($exception->getMessage(), 0, $exception));
}
			}
			return ($Post);
			}

			/**
			 * formats the state variables for JSON serialization
			 *
			 * @return array resulting state variables to serialize
			 **/

			public function jsonSerialize() : array {
				$fields = get_object_vars($this);
				$fields["postId"] = $this->postId->toString();
				$fields["postOrganizationId"] = $this->postOrganizationId->toString();
			}

