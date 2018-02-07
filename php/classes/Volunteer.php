<?php
namespace Edu\Cnm\FeedPast;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Volunteer class for capstone project
 *
 * This is a top level entity for Volunteer in the capstone project
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class Volunteer implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this Volunteer; this is the primary key
	 * @var Uuid $volunteerId
	 **/
	private $volunteerId;
	/**
	 * token handed out to verify that the Volunteer is valid and not malicious
	 * @var string $volunteerActivationToken
	 **/
	private $volunteerActivationToken;
	/**
	 * availability for this Volunteer
	 * @var string $volunteerAvailability
	 **/
	private $volunteerAvailability;
	/**
	 * email for this Volunteer; this is a unique index
	 * @var string $volunteerEmail
	 **/
	private $volunteerEmail;
	/**
	 * hash for Volunteer password
	 * @var string $volunteerHash
	 **/
	private $volunteerHash;
	/**
	 * name for this Volunteer
	 * @var string $volunteerName
	 **/
	private $volunteerName;
	/**
	 * phone number for this Volunteer
	 * @var string $volunteerPhone
	 **/
	private $volunteerPhone;
	/**
	 * salt for Volunteer password
	 * @var string $volunteerSalt
	 **/
	private $volunteerSalt;

	/**
	 * constructor for this Volunteer
	 *
	 * @param Uuid|string $newVolunteerId id of this Volunteer or null if a new Volunteer
	 * @param string $newVolunteerActivationToken token to safe guard against malicious accounts
	 * @param string $newVolunteerAvailability string containing availability of this Volunteer
	 * @param string $newVolunteerEmail string containing email of this Volunteer
	 * @param string $newVolunteerHash string containing password hash
	 * @param string $newVolunteerName string containing name of this Volunteer
	 * @param string $newVolunteerPhone string containing phone number of this Volunteer
	 * @param string $newVolunteerSalt string containing password salt
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if a data type violates a data hint
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
public function __construct($newVolunteerId, ?string $newVolunteerActivationToken, ?string $newVolunteerAvailability, string $newVolunteerEmail, string $newVolunteerHash, string $newVolunteerName, string $newVolunteerPhone, string $newVolunteerSalt) {
	try {
		$this->setVolunteerId($newVolunteerId);
		$this->setVolunteerActivationToken($newVolunteerActivationToken);
		$this->setVolunteerAvailability($newVolunteerAvailability);
		$this->setVolunteerEmail($newVolunteerEmail);
		$this->setVolunteerHash($newVolunteerHash);
		$this->setVolunteerName($newVolunteerName);
		$this->setVolunteerPhone($newVolunteerPhone);
		$this->setVolunteerSalt($newVolunteerSalt);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		//determine what exception type was thrown
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}
}
	/**
	 * accessor method for volunteer id
	 *
	 * @return Uuid value of volunteer if (or null if new Volunteer)
	 **/
	public function getVolunteerId() : Uuid {
		return($this->volunteerId);
	}

	/**
	 * mutator method for volunteer id
	 *
	 * @param Uuid|string $newVolunteerId value of new volunteer id
	 * @throws \RangeException if $newVolunteerId is not positive
	 * @throws \TypeError if $newVolunteerId is not positive
	 **/
	public function setVolunteerId($newVolunteerId) : void {
		try {
			$uuid = self::validateUuid($newVolunteerId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the volunteer id
		$this->volunteerId = $uuid;
	}

	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 **/
	public function getVolunteerActivationToken() : ?string {
		return($this->volunteerActivationToken);
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newVolunteerActivationToken
	 * @throws \InvalidArgumentException if the token does not contain only hexadecimal digits
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setVolunteerActivationToken(?string $newVolunteerActivationToken) : void {
		if($newVolunteerActivationToken === null) {
			$this->volunteerActivationToken = null;
			return;
		}
		$newVolunteerActivationToken = strtolower(trim($newVolunteerActivationToken));

		// make sure volunteer activation token contains only hexadecimal digits
		if(ctype_xdigit($newVolunteerActivationToken) === false) {
			throw(new \InvalidArgumentException("volunteer activation token does not contain all hexadecimal digits"));
		}
		// make sure volunteer activation token is exactly 32 characters
		if(strlen($newVolunteerActivationToken) !== 32) {
			throw(new \RangeException("volunteer activation token must be 32 characters"));
		}
		$this->volunteerActivationToken = $newVolunteerActivationToken;
	}

	/**
	 * accessor method for volunteer availability
	 *
	 * @return string value of volunteer availability
	 **/
	public function getVolunteerAvailability() : ?string {
		return($this->volunteerAvailability);
	}

	/**
	 * mutator method for volunteer availability
	 *
	 * @param string $newVolunteerAvailability new value of volunteer availability
	 * @throws \InvalidArgumentException if $newVolunteerAvailability is not a string or insecure
	 * @throws \RangeException if $newVolunteerAvailability is > 255 characters
	 * @throws \TypeError if $newVolunteerAvailability is not a string
	 **/
	public function setVolunteerAvailability(?string $newVolunteerAvailability) : void {
		// verify the volunteer availability is secure
		$newVolunteerAvailability = trim($newVolunteerAvailability);
		$newVolunteerAvailability = filter_var($newVolunteerAvailability, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if($newVolunteerAvailability == null) {
			$this->volunteerAvailability = null;
			return;
		}
		// verify the volunteer availability will fit in the database
		if(strlen($newVolunteerAvailability) > 255) {
			throw(new \RangeException("volunteer availability is greater than 255 characters"));
		}
		// store the volunteer availability
		$this->volunteerAvailability = $newVolunteerAvailability;
	}

	/**
	 * accessor method for volunteer email
	 *
	 * @return string value of volunteer email
	 **/
	public function getVolunteerEmail() : string {
		return $this->volunteerEmail;
	}

	/**
	 * mutator method for volunteer email
	 *
	 * @param string $newVolunteerEmail new value of volunteer email
	 * @throws \InvalidArgumentException if $newVolunteerEmail is not a valid email or insecure
	 * @throws \RangeException if $newVolunteerEmail is > 128 characters
	 * @throws \TypeError if $newVolunteerEmail is not a string
	 **/
	public function setVolunteerEmail(string $newVolunteerEmail) : void {
		// verify the volunteer email is secure
		$newVolunteerEmail = trim($newVolunteerEmail);
		$newVolunteerEmail = filter_var($newVolunteerEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newVolunteerEmail) === true) {
			throw(new \InvalidArgumentException("volunteer email is empty or insecure"));
		}
		// verify the volunteer email will fit in the database
		if(strlen($newVolunteerEmail) > 128) {
			throw(new \RangeException("volunteer email is greater than 128 characters"));
		}
		// store the volunteer email
		$this->volunteerEmail = $newVolunteerEmail;
	}

	/**
	 * accessor method for volunteer hash password
	 *
	 * @return string value of hash
	 **/
	public function getVolunteerHash() : string {
		return $this->volunteerHash;
	}

	/**
	 * mutator method for volunteer hash password
	 *
	 * @param string $newVolunteerHash new value of volunteer hash
	 * @throws \InvalidArgumentException if $newVolunteerHash is empty or insecure
	 * @throws \RangeException if $newVolunteerHash is not 128 characters
	 * @throws \TypeError if $newVolunteerHash is not a string
	 **/
	public function setVolunteerHash(string $newVolunteerHash) : void {
		// enforce the has is properly formatted
		$newVolunteerHash = trim($newVolunteerHash);
		$newVolunteerHash = strtolower($newVolunteerHash);
		if(empty($newVolunteerHash) === true) {
			throw(new \InvalidArgumentException("volunteer password hash is empty or insecure"));
		}
		// enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newVolunteerHash)) {
			throw(new \InvalidArgumentException("volunteer password hash does not contain all hexadecimal digits"));
		}
		// enforce that the hash is exactly 128 characters
		if(strlen($newVolunteerHash) !== 128) {
			throw(new \RangeException("volunteer password hash must be 128 characters"));
		}
		// store the volunteer hash
		$this->volunteerHash = $newVolunteerHash;
	}
	/**
	 * accessor method for volunteer name
	 *
	 * @return string value of volunteer name
	 **/
	public function getVolunteerName() : string {
		return($this->volunteerName);
	}

	/**
	 * mutator method for volunteer name
	 *
	 * @param string $newVolunteerName new value of volunteer name
	 * @throws \InvalidArgumentException if $newVolunteerName is not a string or insecure
	 * @throws \RangeException if $newVolunteerName is > 255 characters
	 * @throws \TypeError if $newVolunteerName is not a string
	 **/
	public function setVolunteerName(string $newVolunteerName) : void {
		// verify the volunteer name is secure
		$newVolunteerName = trim($newVolunteerName);
		$newVolunteerName = filter_var($newVolunteerName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newVolunteerName) === true) {
			throw(new \InvalidArgumentException("volunteer name is empty or insecure"));
		}
		// verify the volunteer name will fit in the database
		if(strlen($newVolunteerName) > 255) {
			throw(new \RangeException("volunteer name is greater than 255 characters"));
		}
		// store the volunteer name
		$this->volunteerName = $newVolunteerName;
	}

	/**
	 * accessor method for volunteer phone number
	 *
	 * @return string value of volunteer phone
	 **/
	public function getVolunteerPhone() : string {
		return($this->volunteerPhone);
	}

	/**
	 * mutator method for volunteer phone number
	 *
	 * @param string $newVolunteerPhone new value of volunteer phone
	 * @throws \InvalidArgumentException if $newVolunteerPhone is not a string or insecure
	 * @throws \RangeException if $newVolunteerPhone is > 32 characters
	 * @throws \TypeError if $newVolunteerPhone is not a string
	 **/
	public function setVolunteerPhone(string $newVolunteerPhone) : void {
		// verify the volunteer phone number is secure
		$newVolunteerPhone = trim($newVolunteerPhone);
		$newVolunteerPhone = filter_var($newVolunteerPhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newVolunteerPhone) === true) {
			throw(new \InvalidArgumentException("volunteer phone is empty or insecure"));
		}
		// verify the volunteer phone number will fit in the database
		if(strlen($newVolunteerPhone) > 32) {
			throw(new \RangeException("volunteer phone is greater than 32 characters"));
		}
		// store the volunteer phone number
		$this->volunteerPhone = $newVolunteerPhone;
	}

	/**
	 * accessor method for volunteer salt
	 *
	 * @return string representation of the salt hexadecimal
	 **/
	public function getVolunteerSalt() : string {
		return $this->volunteerSalt;
	}

	/**
	 * mutator method for volunteer salt
	 *
	 * @param string $newVolunteerSalt
	 * @throws \InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if the volunteer salt is not a string
	 **/
	public function setVolunteerSalt(string $newVolunteerSalt) : void {
		// enforce that the salt is properly formatted
		$newVolunteerSalt = trim($newVolunteerSalt);
		$newVolunteerSalt = strtolower($newVolunteerSalt);

		// enforce that the salt is a string representation of a hexadecimal
		if(!ctype_xdigit($newVolunteerSalt)) {
			throw(new \InvalidArgumentException("volunteer salt does not contain all hexadecimal digits"));
		}
		// enforce that the salt is exactly 64 characters
		if(strlen($newVolunteerSalt) !== 64) {
			throw(new \RangeException("volunteer salt must be 64 characters"));
		}
		// store the salt
		$this->volunteerSalt = $newVolunteerSalt;
	}

	/**
	 * inserts this Volunteer into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO volunteer(volunteerID, volunteerActivationToken, volunteerAvailability, volunteerEmail, volunteerHash, volunteerName, volunteerPhone, volunteerSalt) VALUES (:volunteerID, :volunteerActivationToken, :volunteerAvailability, :volunteerEmail, :volunteerHash, :volunteerName, :volunteerPhone, :volunteerSalt)";
		$statement = $pdo->prepare($query);

		// bind the member variables to te place holders in the template
		$parameters = ["volunteerId" => $this->volunteerId->getBytes(), "volunteerActivationToken" => $this->volunteerActivationToken, "volunteerAvailability" => $this->volunteerAvailability, "volunteerEmail" => $this->volunteerEmail, "volunteerHash" => $this->volunteerHash, "volunteerName" => $this->volunteerName, "volunteerPhone" => $this->volunteerPhone, "volunteerSalt" => $this->volunteerSalt];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Volunteer from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM volunteer WHERE volunteerId = :volunteerId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["volunteerId" => $this->volunteerId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Volunteer in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		// create query template
		$query = "UPDATE volunteer SET volunteerActivationToken = :volunteerActivationToken, volunteerAvailability = :volunteerAvailability, volunteerEmail = :volunteerEmail, volunteerHash = :volunteerHash, volunteerName = :volunteerName, volunteerPhone = :volunteerPhone, volunteerSalt = :volunteerSalt WHERE volunteerId = :volunteerId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["volunteerId" => $this->volunteerId->getBytes(), "volunteerActivationToken" => $this->volunteerActivationToken, "volunteerAvailability" => $this->volunteerAvailability, "volunteerEmail" => $this->volunteerEmail, "volunteerHash" => $this->volunteerHash, "volunteerName" => $this->volunteerName, "volunteerPhone" => $this->volunteerPhone, "volunteerSalt" => $this->volunteerSalt];
		$statement->execute($parameters);
	}

	/**
	 * get this Volunteer by volunteer id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $volunteerId volunteer id to search for
	 * @return Volunteer|null Volunteer found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getVolunteerByVolunteerId(\PDO $pdo, $volunteerId) : ?Volunteer {
		// sanitize the VolunteerId before searching
		try {
			$volunteerId = self::validateUuid($volunteerId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT volunteerID, volunteerActivationToken, volunteerAvailability, volunteerEmail, volunteerHash, volunteerName, volunteerPhone, volunteerSalt FROM volunteer WHERE volunteerId = :volunteerId";
		$statement = $pdo->prepare($query);

		// bind the volunteer id to the place holder in the template
		$parameters = ["volunteerId" => $volunteerId->getBytes()];
		$statement->execute($parameters);

		// grab the volunteer from mySQL
		try {
			$volunteer = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$volunteer = new Volunteer($row["volunteerId"], $row["volunteerActivationToken"], $row["volunteerAvailability"], $row["volunteerEmail"], $row["volunteerHash"], $row["volunteerName"], $row["volunteerPhone"], $row["volunteerSalt"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($volunteer);
	}

	/**
	 * gets the Volunteer by volunteer activation token
	 *
	 * @param string $volunteerActivationToken
	 * @param \PDO object $pdo
	 * @return Volunteer|null Volunteer or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getVolunteerByVolunteerActivationToken(\PDO $pdo, string $volunteerActivationToken) : ?Volunteer {
		// make sure the activation token is in the right format and a string representation of a hexadecimal
		$volunteerActivationToken = trim($volunteerActivationToken);
		if(ctype_xdigit($volunteerActivationToken) === false) {
			throw(new \InvalidArgumentException("volunteer activation token is empty or in the wrong format"));
		}

		// create the query template
		$query = "SELECT volunteerID, volunteerActivationToken, volunteerAvailability, volunteerEmail, volunteerHash, volunteerName, volunteerPhone, volunteerSalt FROM volunteer WHERE volunteerActivationToken = :volunteerActivationToken";
		$statement = $pdo->prepare($query);

		// bind the volunteer activation token to the place holder in the template
		$parameters = ["volunteerActivationToken" => $volunteerActivationToken];
		$statement->execute($parameters);

		// grab the Volunteer from mySQL
		try {
			$volunteer = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$volunteer = new Volunteer($row["volunteerId"], $row["volunteerActivationToken"], $row["volunteerAvailability"], $row["volunteerEmail"], $row["volunteerHash"], $row["volunteerName"], $row["volunteerPhone"], $row["volunteerSalt"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}

	/**
	 * gets this Volunteer by email
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $volunteerEmail email to search for
	 * @return Volunteer|null Volunteer found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getVolunteerByVolunteerEmail(\PDO $pdo, $volunteerEmail) : ?Volunteer {
		// sanitize the email before searching
		$volunteerEmail = trim($volunteerEmail);
		$volunteerEmail = filter_var($volunteerEmail, FILTER_VALIDATE_EMAIL);
		if(empty($volunteerEmail) === true) {
			throw(new \PDOException("volunteer email is invalid"));
		}

		// create query template
		$query = "SELECT volunteerID, volunteerActivationToken, volunteerAvailability, volunteerEmail, volunteerHash, volunteerName, volunteerPhone, volunteerSalt FROM volunteer WHERE volunteerEmail = :volunteerEmail";
		$statement = $pdo->prepare($query);

		// bind the volunteer email to the place holder in the template
		$parameters = ["volunteerEmail" => $volunteerEmail];
		$statement->execute($parameters);

		// grab the volunteer from mySQL
		try {
			$volunteer = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$volunteer = new Volunteer($row["volunteerId"], $row["volunteerActivationToken"], $row["volunteerAvailability"], $row["volunteerEmail"], $row["volunteerHash"], $row["volunteerName"], $row["volunteerPhone"], $row["volunteerSalt"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($volunteer);
	}

	/**
	 * gets the Volunteer by name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $volunteerName name to search for
	 * @return \SPLFixedArray of all volunteers found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getVolunteerByVolunteerName(\PDO $pdo, string $volunteerName) : \SPLFixedArray {
		// sanitize the name before searching
		$volunteerName = trim($volunteerName);
		$volunteerName = filter_var($volunteerName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($volunteerName) === true) {
			throw(new \PDOException("not a valid volunteer name"));
		}

		// create the query template
		$query = "SELECT volunteerID, volunteerActivationToken, volunteerAvailability, volunteerEmail, volunteerHash, volunteerName, volunteerPhone, volunteerSalt FROM volunteer WHERE volunteerName = :volunteerName";
		$statement = $pdo->prepare($query);

		// bind the volunteer name to the place holder in the template
		$parameters = ["volunteerName" => $volunteerName];
		$statement->execute($parameters);

		// grab the volunteer from mySQL
		$volunteers = new \SPLFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$volunteer = new Volunteer($row["volunteerId"], $row["volunteerActivationToken"], $row["volunteerAvailability"], $row["volunteerEmail"], $row["volunteerHash"], $row["volunteerName"], $row["volunteerPhone"], $row["volunteerSalt"]);
				$volunteers[$volunteers->key()] = $volunteer;
				$volunteers->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($volunteers);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["volunteerId"] = $this->volunteerId->toString();
		unset($fields["volunteerActivationToken"]);
		unset($fields["volunteerHash"]);
		unset($fields["volunteerSalt"]);

		return($fields);
	}
}