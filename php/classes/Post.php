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
namespace FeedingOurPast;
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
use Ramsey\Uuid\Uuid;

/**
 * The class is definded and set to Post
 * This class uses Uuids
 *
 *    This object is based on the post table in feeding-our-past.sql
 *    Class Name and Namespace are PSR4
 *    "implements \JsonSerializable" removed until later
**/
class Post{
use ValidateUuid;

/**
 * Post uses postId as the primary key
 * postId is the primary key
 * postId is the post's unique id
 * @var Uuid $postId
 * articleId state set to private
*/
private $postId;

/**
 * Post uses postOrganizationId as the foreign key
 * postOrganizationId is the foreign key
 * postOrganizationId is the posting organizations unique id
 * @var Uuid postOrganizationId
 * postOrganizationId state set to private
*/
	private $postOrganizationId;

/**
 * Post uses postContent as an element
 * This is the content of the post
 * @var varchar postContent
 * postContent state set to private
*/
private $postContent;

/**
 * Post uses postEndDateTime as an element
 * This is the date that the post is to be removed from service
 * @var datetime postEndDateTime
 * postEndDateTime state set to private
*/
	private $postEndDateTime;

/**
 * Post uses postImageUrl as an element
 * This is the url of the image associated with the content
 * @var varchar postImageUrl
 * postImageUrl state set to private
*/
private $postImageUrl;

/**
 * Post uses postStartDateTime as an element
 * This is the start date and time of the post
 * @var datetime postStartDateTime
 * postStartDateTime state set to private
 */
private $postStartDateTime;

/**
 * Post uses postTitle as an element
 * This is the title of the post
 * @var varchar postTitle
 * postTitle state set to private
 */
private $postTitle;

/**
 * constructor for Post
 *
 * Constructs the object post and associated object's states
 * @param Uuid $newPostId is the poster's unique and required id
 * @param Uuid $newPostOrganizationId is the posting organization's unique and required id
 * @param varchar $newPostContent is the content of the post
 * @param datetime $newPostEndDateTime is the required date and time the post may be removed
 * @param varchar $newPostImageUrl is the location of the image that may accompany the post
 * @param datetime $newPostStartDateTime is the required date and time the post may be added
 * @param varchar $newPostTitle is the title of the post
 * @throws \InvalidArgumentException if data types are not valid
 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
 * @throws \TypeError if data types violate type hints
 * @throws \Exception if some other exception occurs
 * @Documentation <https://php.net/manual/en/language.oop5.decon.php>
 * @throws and @Documentation notes are straight from Dylan McDonald's code template
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
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/*
	 * accessor method for articleId
	 * @return Uuid value of articleId
	 *
	 *    Is accessor/get the php equivilant of sql's select?
	 *    How do I confirm this command was propperly issued?
	*/
	public function getArticleId() : Uuid {
		return($this->articleId);
	}

	/*
	 * Mutator method for article id
	 * @param Uuid/string $newArticleId new value of articleId
	 * @throws \RangeException if $newArticleId is not positive
	 * @throws \TypeError if $newArticleId is not a uuid or string
	 *    @param, @throws, and exceptions are straight from Dylan McDonald's code
	*/
	public function setArticleId( $newArticleId) : void {
		try {
			$uuid = self::validateUuid($newArticleId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		/*
		* Convert and store the articleId
		 *    Is convert for uuid's only?
		*/
		$this->articleId = $uuid;
	}

	/**
	 * accessor method for userid
	 * @return Uuid value of userid
	 **/
	public function getUserId() : Uuid{
		return($this->userId);
	}

	/**
	 * mutator method for userid
	 *
	 * @param Uuid/string $newArticleId new value of userId
	 * @throws \RangeException if $newUserId is not positive
	 * @throws \TypeError if $newUserId is not a string or uuid
	 **/
	public function setUserId( $newUserId) : void {
		try {
			$uuid = self::validateUuid($newUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		/*
		 * Convert and store the userid
		*/
		$this->userId = $uuid;
	}

	/*
	 * accessor method for approximateReadTime
	 * @return int value of approximateReadTime
	 *    Note how this is :int is this correct? Where :int set?
	*/
	public function getApproximateReadTime() : int{
		return($this->approximateReadTime);
	}

	/*
	 * mutator method for approximateReadTime
	 * @param int $newApproximateReadTime new value of approximateReadTime
	 * @throws \InvalidArgumentException if $newApproximate is not an int or insecure
	 * @throws \RangeException if $newApproximateReadTime is > 1000
	 * @throws \TypeError if $newApproximateReadTime is not an int
	*/
	public function setApproximateReadTime($newApproximateReadTime) {
		$newApproximateReadTime = filter_var($newApproximateReadTime, FILTER_VALIDATE_INT);
		if($newApproximateReadTime === false) (
			throw(new UnexpectedValueException("Approximate Read Time is not a valid integer"));
		)
		/*
 * convert and store the approximate read time
*/
		$this->approximateReadTime = intval($newApproximateReadTime);
	}

	/*
	 * accessor method for articleTitle
	 * @return string value of articleTitle
	 */
	public function getarticleTitle() : string {
		return($this->articleTitle);
	}

	/*
	 * mutator method for articleTitle
	 * @throws \InvalidArgumentException if $newArticleTitle is not a valid string
		 **/
	public function setArticleTitle($newArticleTitle) {
		return($this->articleTitle);
		$this->articleTitle = $newArticleTitle;
	}

	/**
	 * inserts this Article into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO article(articleId, userId, articleTitle, articleTitle) VALUES(:articleId, :userId, :approximateReadTime, :articleTitle)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["articleId" => $this->articleId->getBytes(), "userId" => $this->userId->getBytes(), "approximateReadTime" => $this->approximateReadTime, "articleTitle" => $this->articleTitle];
		$statement->execute($parameters);
	}


	/**
	 * deletes this article from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM article WHERE articleId = :articleId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["articleId" => $this->articleId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Article in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE article SET userId = :userId, approximateReadTime = :approximateReadTime, articleTitle = :articleTitle WHERE articleId = :articleId";
		$statement = $pdo->prepare($query);

		$parameters = ["articleId" => $this->articleId->getBytes(),"userId" => $this->userId->getBytes(), "approximateReadTime" => $this->approximateReadTime, "articleTitle" => $this->articleTitle];
		$statement->execute($parameters);
	}

	/**
	 * gets the Article by articeId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $articleId article id to search for
	 * @return Article|null Article found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getArticleByArticleId(\PDO $pdo, $articleId) : ?Article {
		// sanitize the articleId before searching
		try {
			$articleId = self::validateUuid($articleId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT articleId, userId, approximateReadTime, articleTitle FROM article WHERE articleId = :articleId";
		$statement = $pdo->prepare($query);
		// bind the article id to the place holder in the template
		$parameters = ["articleId" => $articleId->getBytes()];
		$statement->execute($parameters);

		// grab the article from mySQL
		try {
			$article = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$article = new Article($row["articleId"], $row["userId"], $row["approximateReadTime"], $row["articleTitle"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($article);
	}

	/**
	 * gets the Article by userId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $userId to search by
	 * @return \SplFixedArray SplFixedArray of Articles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getArticleByUserId(\PDO $pdo, $userId) : \SplFixedArray {

		try {
			$userId = self::validateUuid($userId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT articleId, userId, approximateReadTime, articleTitle FROM article WHERE userId = :userId";
		$statement = $pdo->prepare($query);
		// bind the user id to the place holder in the template
		$parameters = ["userId" => $userId->getBytes()];
		$statement->execute($parameters);
		// build an array of articles
		$articles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$article = new Article($row["articleId"], $row["userId"], $row["approximateReadTime"], $row["articleTitle"]);
				[$article->key()] = $article;
				$articles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($articles);
	}

	/**
	 * gets the Articles by approximateReadTime
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $approximateReadTime article content to search for
	 * @return \SplFixedArray SplFixedArray of Articles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getArticleByApproximateReadTime(\PDO $pdo, $approximateReadTime) : \SplFixedArray {
		{
			// create query template
			$query = "SELECT articleId, userId, approximateReadTime, articleTitle FROM article WHERE approximateReadTime = :approximateReadTime";
			$statement = $pdo->prepare($query);
			// bind the content to the place holder in the template
			$approximateReadTime = "%$approximateReadTime%";
			$parameters = ["approximateReadTime" => $approximateReadTime];
			$statement->execute($parameters);

			// build an array of articles
			$articles = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$articles = new Articles($row["articleId"], $row["userId"], $row["approximateReadTime"], $row["articleTitle"]);
					$articles[$articles->key()] = $articles;
					$articles->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
		}
		return($articles);

		/**
		 * gets all Articles
		 *
		 * @param \PDO $pdo PDO connection object
		 * @return \SplFixedArray SplFixedArray of Articles found or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static__function getAllArticles(\PDO $pdo) : \SPLFixedArray {
			// create query template
			$query = "SELECT articleID, userId, approximateReadTime, articleTitle FROM article";
			$statement = $pdo->prepare($query);
			$statement->execute();

			// build an array of articles
			$articles = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$article = new Article($row["articleId"], $row["userId"], $row["approximateReadTime"], $row["articleTitle"]);
					$articles[$articles->key()] = $article;
					$articles->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return ($articles);

			/**
			 * formats the state variables for JSON serialization
			 *
			 * @return array resulting state variables to serialize
			 **/

			public function jsonSerialize() : array {
				$fields = get_object_vars($this);
				$fields["articleId"] = $this->articleId->toString();
				$fields["userId"] = $this->userId->toString();
			}

