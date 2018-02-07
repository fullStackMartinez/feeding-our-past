<?php

namespace Edu\Cnm\FeedPast;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 * Cross Section of a Twitter Like
 *
 * This is a cross section of what probably occurs when a user likes a Tweet. It is an intersection table (weak
 * entity) from an m-to-n relationship between Profile and Tweet.
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

		$this->favoritePostId = $Uuid;
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
	 * @param string | Uuid $newFavoriteVolunteerId new value of favorite volunteer id
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
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		// create query template
		$query = "INSERT INTO 'favorite'(favoriteProfileId, favoritePostId) Values (:favoritePostI, favoriteVolunteerId)";
		$statement = $pdo->prepare($query);
	}

	// bind the member variables to the place holders in the template
	$parameters = ["favoriteProfileId" => $this->favoriteProfileId->getBytes(), "favoriteVolunteerId" =>$this->favoriteVolunteerId->getBytes()]
	$statement->execute($parameters);
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/

	pulic function jsonserialize() {
		$fields = get_object_vars($this);


		$fields["favoritePostId"] = $this->favoritePostId;
		$fields["favoriteVolunteerId"] = $this->favoriteVolunteerId;

		return ($fields);


}}
