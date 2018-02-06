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
	 * @var $volunteerActivationToken
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
	 * @var $volunteerHash
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
	 * @var $volunteerSalt
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
		// make sure volunteer activation token has length 32
		if(strlen($newVolunteerActivationToken) !== 32) {
			throw(new \RangeException("volunteer activation token must be length 32"));
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