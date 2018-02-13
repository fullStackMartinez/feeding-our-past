<?php

namespace Edu\Cnm\FeedPast;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 *
 * This is a cross section of what probably occurs when a user Favorites a Post. It is an intersection table (weak
 * entity) from an m-to-n relationship between Post and Volunteer.
 *
 * @author Jeffrey Brink <jeffreybrink@gmx.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/




class Favorite implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;

	/**
	 * id of the post that this favorite is for;  this is a foriegn key
	 * @var Uuid $favoritePostId ;
	 **/

	private $favoritePostId;

	/**
	 * id of the organization that made this favorite; this is a foriegn key
	 * @var Uuid $favoriteVolunteerId
	 **/

	private $favoriteVolunteerId;

	/**
	 * constructor for this class
	 *
	 * @param string|Uuid $newFavoritePostId id of the parent post
	 * @param string|Uuid $newFavoriteVolunteerId id of the parent volunteer
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if the data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception is thrown
	 **/

	public function __construct($newFavoritePostId, $newFavoriteVolunteerId) {
		try {
			$this->setFavoritePostId($newFavoritePostId);
			$this->setFavoriteVolunteerId		($newFavoriteVolunteerId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			// determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
}

	/**
	 * accessor method for favorite post id
	 *
	 * @return Uuid value of favorite post id
	 **/

	public function getFavoritePostId(): Uuid {
		return ($this->favoritePostId);
	}

	/**
	 * mutator method for favorite post id
	 *
	 * @param string | Uuid $newFavoritePostId new value of favorite post id
	 * @throws \RangeException if $newFavoritePostId is not positive
	 * @throws \TypeError if $newFavoritePostId is not an integer
	 **/

	public function setFavoritePostId($newFavoritePostId) : void {
		try {
			$uuid = self::validateUuid($newFavoritePostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the post id

		$this->favoritePostId = $uuid;
	}

	/**
	 * accessor method for favorite volunteer id
	 *
	 * @return Uuid value of favoriteVolunteerId
	 **/

	public function getFavoriteVolunteerId() : Uuid {
		return($this->favoriteVolunteerId);
	}

	/**
	 * mutator method for favorite volunteer id
	 *
	 * @param | Uuid $newFavoriteVolunteerId new value of favorite volunteer id
	 * @throws \RangeException if $newfavoriteVolunteer is not positive
	 * @throws \TypeError if $newFavoriteVolunteerID is not an integer
	 **/

	public function setFavoriteVolunteerId($newFavoriteVolunteerId) : void {
		try {
			$uuid = self::validateUuid($newFavoriteVolunteerId);
		} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the volunteer id
		$this->favoriteVolunteerId = $uuid;
	}

	/**
	 * inserts this favorite into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
			$query = "INSERT INTO favorite (favoritePostId, favoriteVolunteerId) VALUES(:favoritePostId, :favoriteVolunteerId)";
				$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
					$parameters = ["favoritePostId" => $this->favoritePostId->getBytes(), "favoriteVolunteerId" => $this->favoriteVolunteerId->getBytes()];
						$statement->execute($parameters);
	}

	/**
	 * deletes this Favorite from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/

	public function delete(\PDO $pdo) : void {
		// create query template
			$query = "DELETE FROM favorite WHERE favoritePostId = :favoritePostId AND favoriteVolunteerId = :favoriteVolunteerId";
				$statement = $pdo->prepare($query);
		//bind the member variables to the placeholders in the template
					$parameters = ["favoritePostId" => $this->favoritePostId->getBytes(), "favoriteVolunteerId" => 	$this->favoriteVolunteerId->getBytes()];
						$statement->execute($parameters);
	}

	/**
	 * gets the favorite by post id and volunteer id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid $favoritePostId post id to search for
	 * @param string $favoriteVolunteerId volunteer id to search for
	 * @return favorite |null favorite found or null if not found
	 **/

	public static function getFavoriteByFavoritePostIdAndFavoriteVolunteerId(\PDO $pdo, string $favoritePostId, string $favoriteVolunteerId) : ?favorite{
		//
		try {
			$favoritePostId = self::validateUuid($favoritePostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		try {
			$favoriteVolunteerId = self::validateUuid($favoriteVolunteerId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
			$query = "SELECT favoritePostId, favoriteVolunteerId FROM `favorite` WHERE favoritePostId = :favoritePostId AND favoriteVolunteerId = :favoriteVolunteerId";
				$statement = $pdo->prepare($query);
		// bind the volunteer id and post id to the place holder in the template
					$parameters = ["favoritePostId" => $favoritePostId->getBytes(), "favoriteVolunteerId" => $favoriteVolunteerId->getBytes()];
						$statement->execute($parameters);

		// grab the favorite from mySQL
		try {
			$favorite = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
					$row = $statement->fetch();
					if($row !== false) {
						$favorite = new favorite($row["favoritePostId"], $row["favoriteVolunteerId"]);
			}
		}

		catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($favorite);
	}

	/**
	 * gets the favorite by post id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $favoritePostId post id to search for
	 * @return \SplFixedArray SplFixedArray of favorites found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public static function getFavoriteByFavoritePostId(\PDO $pdo, string $favoritePostId) : \SPLFixedArray {
		try {
			$favoritePostId = self::validateUuid($favoritePostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
			$query = "SELECT favoritePostId, favoriteVolunteerId FROM `favorite` WHERE favoritePostId = :favoriteVolunteerId";
				$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
					$parameters = ["favoritePostId" => $favoritePostId->getBytes()];
						$statement->execute($parameters);

		// build an array of favorites
			$favorites = new \SplFixedArray($statement->rowCount());
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {

			try {
				$favorite = new Favorite($row["favoritePostId"], $row["favoriteVolunteerId"]);
					$favorites[$favorites->key()] = $favorite;
						$favorites->next();
			}
			catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it

				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($favorites);
	}

	/**
	 * gets the favorite by volunteer id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $favoriteVolunteerId volunteer id to search for
	 * @return \SplFixedArray array of favorites found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public static function getFavoriteByFavoriteVolunteerId(\PDO $pdo, string $favoriteVolunteerId) : \SplFixedArray {
		try {
			$favoriteVolunteerId = self::validateUuid($favoriteVolunteerId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT favoritePostId, favoriteVolunteerId FROM `favorite` WHERE favoriteVolunteerId = :favoriteVolunteerId";
			$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
				$parameters = ["favoriteVolunteerId" => $favoriteVolunteerId->getBytes()];
					$statement->execute($parameters);

		// build the array of favorites
		$favorites = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {

			try {
				$favorite = new Favorite($row["favoritePostId"], $row["favoriteVolunteerId"]);
					$favorites[$favorites->key()] = $favorite;
						$favorites->next();
			}
			catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return ($favorites);
	 }


		/**
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/

	public function jsonserialize() {
		$fields = get_object_vars($this);


		$fields["favoritePostId"] = $this->favoritePostId;
		$fields["favoriteVolunteerId"] = $this->favoriteVolunteerId;

		return ($fields);
		}
	}
