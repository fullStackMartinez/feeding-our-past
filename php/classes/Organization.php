<?php
namespace Edu\Cnm\FeedPast;

require_once("autoload.php");
require_once(dirname(__DIR__,2) . "/vendor/autoload.php");



use Ramsey\Uuid\Uuid;

/**
 * This is the organization class
 *
 * This class is the organization that will be registering with our website writing posts in order to market their events. Their information will
 * be stored and they will have the ability to write posts
 *
 * @author Esteban Martinez <fullstackmartinez@gmail.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 1.0.0
 **/
class Organization implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * this is organization id, this will serve as the primary key for this class
	 * @var Uuid $organizationId
	 **/
	private $organizationId;
	/**
	 * this is the activation token which allows organizations to validate the organization profile and security
	 * @var $organizationActivationToken
	 **/
	private $organizationActivationToken;
	/**
	 * this is the organization city, here will be the city of the organization
	 * @var string $organizationAddressCity
	 **/
	private $organizationAddressCity;
	/**
	 * this is the organization state, the state that the city is a part of for the organization
	 * @var string $organizationAddressState
	 **/
	private $organizationAddressState;
	/**
	 * this is the organization street, the street address of the organization
	 * @var string $organizationAddressStreet
	 **/
	private $organizationAddressStreet;
	/**
	 * this is the organization zip code, the official mailing zip code of the registered organization profile
	 * @var string $organizationAddressZip;
	 **/
	private $organizationAddressZip;
	/**
	 * this state variable will determine if the organization accepts food donations
	 * @var string $organizationDonationAccepted
	 **/
	private $organizationDonationAccepted;
	/**
	 * this is the organizations email address used for communication, this is unique
	 * @var string @organizationEmail
	 **/
	private $organizationEmail;
	/**
	 * this is the hash for the organization profile password
	 * @var $organizationHash
	 **/
	private $organizationHash;
	/**
	 * this will determine the operating hours of the organization
	 * @var string $organizationHoursOpen
	 **/
	private $organizationHoursOpen;
	/**
	 * this is the official display name of the organization
	 * @var string $organizationName
	 **/
	private $organizationName;
	/**
	 * this is the contact phone number of the organization
	 * @var string $organizationPhone;
	 **/
	private $organizationPhone;
	/**
	 * this is the salt for password
	 * @var $organizationSalt;
	 **/
	private $organizationSalt;
	/**
	 * this is the URL of the organization, this is unique
	 * @var string $organizationUrl
	 **/
	private $organizationUrl;

	/**
	 * constructor for this Organization
	 *
	 * @param string|Uuid $newOrganizationId will give id of the organizations profile, or null if a brand new profile
	 * @param string $newOrganizationActivationToken string that gives security to the profile
	 * @param string $newOrganizationAddressCity string of the organization profile city
	 * @param string $newOrganizationAddressState string of the organization profile state
	 * @param string $newOrganizationAddressStreet string of the organization profile street
	 * @param string $newOrganizationAddressZip string of the organization profile Zip code
	 * @param string $newOrganizationDonationAccepted string that tells whether the organization accepts food donations
	 * @param string $newOrganizationEmail string that contains organization email
	 * @param string $newOrganizationHash string that contains the profile hash
	 * @param string $newOrganizationHoursOpen string that indicates the hours of operation
	 * @param string $newOrganizationName string of the name of organization
	 * @param string $newOrganizationPhone string that contains phone contact information of organization
	 * @param string $newOrganizationSalt string containing salt for profile password
	 * @param string $newOrganizationUrl string containing organization url
	 * @throws \InvalidArgumentException if any data types are invalid
	 * @throws \RangeException if the data values, for example string lengths exceed limit, are not valid
	 * @throws \TypeError will be thrown if data types have errors
	 * @throws \Exception if there are other exceptions that occur
	 * @Documentation http://php.net/manual/en/language.exceptions.php
	 * @Documentation https://secure.php.net/manual/en/language.oop5.decon.php
	 **/
	function __construct($newOrganizationId, ?string $newOrganizationActivationToken, string $newOrganizationAddressCity, string $newOrganizationAddressState, string $newOrganizationAddressStreet, string $newOrganizationAddressZip, string $newOrganizationDonationAccepted, string $newOrganizationEmail, string $newOrganizationHash, string $newOrganizationHoursOpen, string $newOrganizationName, string $newOrganizationPhone, string $newOrganizationSalt, ?string $newOrganizationUrl) {
		try {
			$this->setOrganizationId($newOrganizationId);
			$this->setOrganizationActivationToken($newOrganizationActivationToken);
			$this->setOrganizationAddressCity($newOrganizationAddressCity);
			$this->setOrganizationAddressState($newOrganizationAddressState);
			$this->setOrganizationAddressStreet($newOrganizationAddressStreet);
			$this->setOrganizationAddressZip($newOrganizationAddressZip);
			$this->setOrganizationDonationAccepted($newOrganizationDonationAccepted);
			$this->setOrganizationEmail($newOrganizationEmail);
			$this->setOrganizationHash($newOrganizationHash);
			$this->setOrganizationHoursOpen($newOrganizationHoursOpen);
			$this->setOrganizationName($newOrganizationName);
			$this->setOrganizationPhone($newOrganizationPhone);
			$this->setOrganizationSalt($newOrganizationSalt);
			$this->setOrganizationUrl($newOrganizationUrl);
		} catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
			//determine which of the exceptions will be thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for organization id, primary key
	 *
	 * @return Uuid value of the organization profile id, or null if brand new organization profile
	 **/
	public function getOrganizationId(): Uuid {
		return ($this->organizationId);
	}

	/**
	 * mutator method for primary key organization id
	 *
	 * @param Uuid| string $newOrganizationId the value of the primary key
	 * @throws |\RangeException if $newOrganizationId is not positive
	 * @throws |\TypeError if there is a type error in the organization id
	 **/
	public function setOrganizationId($newOrganizationId): void {
		try {
			$uuid = self::validateUuid($newOrganizationId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the organization id
		$this->organizationId = $uuid;
	}

	/**accessor method for organization activation token
	 *
	 * @return string value of the activation token
	 **/
	public function getOrganizationActivationToken() : ?string {
		return ($this->organizationActivationToken);
	}

	/**
	 * mutator method for organization account activation token
	 *
	 * @param string $newOrganizationActivationToken
	 * @throws \InvalidArgumentException will be thrown if the activation token is not safe or not a string
	 * @throws \RangeException will be thrown if activation token is not exactly 32 characters in length
	 * @throws \TypeError if the token is not a string
	 **/
	public function setOrganizationActivationToken(?string $newOrganizationActivationToken): void {
		if($newOrganizationActivationToken === null) {
			$this->organizationActivationToken = null;
			return;
		}
		$newOrganizationActivationToken = strtolower(trim($newOrganizationActivationToken));
		if(ctype_xdigit($newOrganizationActivationToken) === false) {
			throw(new\RangeException("sorry, but the user activation token you have used is invalid"));
		}

		//this validates the activation token to be only 32 characters
		if(strlen($newOrganizationActivationToken) !== 32) {
			throw(new\RangeException("sorry, but the user activation token must be exactly 32 characters long"));
		}//stores the activation token for organization profile
		$this->organizationActivationToken = $$newOrganizationActivationToken;
	}

	/**
	 * accessor method for organization city address
	 *
	 * @return string organization city address
	 **/
	public function getOrganizationAddressCity(): string {
		return ($this->organizationAddressCity);
	}

	/**
	 * mutator method for organization city address
	 *
	 * @param string $newOrganizationAddressCity new value of city address
	 * @throws \InvalidArgumentException if $newOrganizationAddressCity is not secure or not a string
	 * @throws \RangeException if $newOrganizationAddressCity is larger than 32 characters long
	 * @throws \TypeError if there is a type error or @newOrganizationAddressCity is not a string
	 **/
	public function setOrganizationAddressCity(string $newOrganizationAddressCity) : void {
		//validate address is safe
		$newOrganizationAddressCity = trim($newOrganizationAddressCity);
		$newOrganizationAddressCity = filter_var($newOrganizationAddressCity, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationAddressCity) ===true) {
			throw(new \InvalidArgumentException("sorry, but city address is unsafe or empty"));
		}
		//validate address is less or equal to 32 characters
		if(strlen($newOrganizationAddressCity) > 32) {
			throw(new \RangeException("sorry, but address must not exceed 32 characters"));
		}
		//save the city of organization address
		$this->organizationAddressCity = $newOrganizationAddressCity;
	}

	/**
	 * accessor method for organization address state
	 *
	 * @return string for the state address
	 **/
	public function getOrganizationAddressState(): string {
		return ($this->organizationAddressState);
	}

	/**
	 * mutator method for address state
	 *
	 * @param string $newOrganizationAddressState gives the value for the address state
	 * @throws \InvalidArgumentException if $newOrganizationAddressState is not safe or not a string
	 * @throws \RangeException if address state is longer than 32 characters
	 * @throws \TypeError if there is an error with the string
	 **/
	public function setOrganizationAddressState(string $newOrganizationAddressState) : void {
		//validate safety of address
		$newOrganizationAddressState = trim($newOrganizationAddressState);
		$newOrganizationAddressState = filter_var($newOrganizationAddressState, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationAddressState) === true) {
			throw(new \InvalidArgumentException("sorry, but this address is unsafe or empty"));
		}
		//validate that address state is less than or equal to 32 characters
		if(strlen($newOrganizationAddressState) > 32) {
			throw(new \RangeException("sorry, but this address must not exceed 32 characters"));
		}
		//save the state of the organizations address
		$this->organizationAddressState = $newOrganizationAddressState;
	}

	/**
	 * accessor method for the street address of organization
	 *
	 * @return string for the street address
	 **/
	public function getOrganizationAddressStreet(): string {
		return ($this->organizationAddressStreet);
	}

	/**
	 * mutator method for street address
	 *
	 * @param string $newOrganizationAddressStreet
	 * @throws \InvalidArgumentException if $newOrganizationAddressStreet is not safe or not a string
	 * @throws \RangeException if street address is not less than or equal to 32 characters
	 * @throws \TypeError if street address has a typo or not a string
	 **/
	public function setOrganizationAddressStreet(string $newOrganizationAddressStreet): void {
		//validate street address security
		$newOrganizationAddressStreet = trim($newOrganizationAddressStreet);
		$newOrganizationAddressStreet = filter_var($newOrganizationAddressStreet, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationAddressStreet) === true) {
			throw(new \InvalidArgumentException("sorry, but the street address is not safe or empty"));
		}
		//verify the street address is within 32 characters
		if(strlen($newOrganizationAddressStreet) > 32) {
			throw(new \RangeException("sorry, but street address must not be greater that 32 characters"));
		}
		//save the street address
		$this->organizationAddressStreet = $newOrganizationAddressStreet;
	}

	/**
	 * accessor method for the zip code of organization
	 *
	 * @return string for the zip code of organization
	 **/
	public function getOrganizationAddressZip(): string {
		return ($this->organizationAddressZip);
	}

	/**
	 * mutator method for zip code
	 *
	 * @param string $newOrganizationAddressZip
	 * @throws \InvalidArgumentException if $newOrganizationAddressZip is not safe or not a string
	 * @throws \RangeException if zip code is not less than or equal to 32 characters
	 * @throws \TypeError if zip has a typo or not a string
	 **/
	public function setOrganizationAddressZip(string $newOrganizationAddressZip): void {
		//validate street address security
		$newOrganizationAddressZip = trim($newOrganizationAddressZip);
		$newOrganizationAddressZip = filter_var($newOrganizationAddressZip, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationAddressZip) === true) {
			throw(new \InvalidArgumentException("sorry, but the zip code is not safe or empty"));
		}
		//verify the zip code is within 32 characters
		if(strlen($newOrganizationAddressZip) > 32) {
			throw(new \RangeException("sorry, but zip code must not be greater that 32 characters"));
		}
		//save the zip code
		$this->organizationAddressZip = $newOrganizationAddressZip;
	}

	/**
	 * accessor method for donations accepted option
	 *
	 * @return string donations accepted by organization
	 **/
	public function getOrganizationDonationAccepted(): string {
		return ($this->organizationDonationAccepted);
	}

	/**
	 * mutator method for donation accepted string
	 *
	 * @param string $newOrganizationDonationAccepted
	 * @throws \InvalidArgumentException if $newOrganizationDonationAccepted is not safe or not a string
	 * @throws \RangeException if donation accepted response string is not less than or equal to 32 characters
	 * @throws \TypeError if there is a typo or response is not a string
	 **/
	public function setOrganizationDonationAccepted(string $newOrganizationDonationAccepted): void {
		//validate donation accepted string security
		$newOrganizationDonationAccepted = trim($newOrganizationDonationAccepted);
		$newOrganizationDonationAccepted = filter_var($newOrganizationDonationAccepted, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationDonationAccepted) === true) {
			throw(new \InvalidArgumentException("sorry, but the response you gave is not safe or empty"));
		}
		//verify the string is within 32 characters
		if(strlen($newOrganizationDonationAccepted) > 32) {
			throw(new \RangeException("sorry, but the response must not be greater that 32 characters"));
		}
		//save the string
		$this->organizationDonationAccepted = $newOrganizationDonationAccepted;
	}

	/**
	 * accessor method for the email address of organization profile
	 *
	 * @return string for the organization email address
	 **/
	public function getOrganizationEmail(): string {
		return ($this->organizationEmail);
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newOrganizationEmail
	 * @throws \InvalidArgumentException if $newOrganizationEmail is not safe or not a string
	 * @throws \RangeException if email is not less than or equal to 32 characters
	 * @throws \TypeError if email has a typo or not a string
	 **/
	public function setOrganizationEmail(string $newOrganizationEmail): void {
		//validate email security
		$newOrganizationEmail = trim($newOrganizationEmail);
		$newOrganizationEmail = filter_var($newOrganizationEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newOrganizationEmail) === true) {
			throw(new \InvalidArgumentException("sorry, but the email address you have provided is not safe or empty"));
		}
		//verify the email is within 128 characters
		if(strlen($newOrganizationEmail) > 128) {
			throw(new \RangeException("sorry, but email must not be greater that 128 characters"));
		}
		//save the email
		$this->organizationEmail = $newOrganizationEmail;
	}

	/**
	 * accessor method for the password hash of the organization profile
	 *
	 * @return string for password hash
	 **/
	public function getOrganizationHash(): string {
		return ($this->organizationHash);
	}

	/**
	 * mutator method for password hash
	 *
	 * @param string $newOrganizationHash
	 * @throws \InvalidArgumentException if $newOrganizationHash is not safe or not a string
	 * @throws \RangeException if password hash is not less than or equal to 128 characters
	 * @throws \TypeError if password hash has a typo or not a string
	 **/
	public function setOrganizationHash(string $newOrganizationHash): void {
		//validate hash format
		$newOrganizationHash = trim($newOrganizationHash);
		$newOrganizationHash = strtolower($newOrganizationHash);
		if(empty($newOrganizationHash) === true) {
			throw(new \InvalidArgumentException("sorry, but the profile password hash you have provided is not safe or empty"));
		}
		//validate that password hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newOrganizationHash)) {
			throw(new \InvalidArgumentException("sorry, but the profile password hash you have provided is not safe or empty"));
		}
		//validate the password hash is within 128 characters
		if(strlen($newOrganizationHash) !== 128) {
			throw(new \RangeException("sorry, but password hash must be 128 characters"));
		}
		//save the password hash
		$this->organizationHash = $newOrganizationHash;
	}

	/**
	 * accessor method for hours of operation of organization
	 *
	 * @return string hours organization is open
	 **/
	public function getOrganizationHoursOpen(): string {
		return ($this->organizationHoursOpen);
	}

	/**
	 * mutator method for hours of operation string
	 *
	 * @param string $newOrganizationHoursOpen hours the organization is open
	 * @throws \InvalidArgumentException if $newOrganizationHoursOpen is not safe or not a string
	 * @throws \RangeException if hours open string is not less than or equal to 64 characters
	 * @throws \TypeError if there is a typo or response is not a string
	 **/
	public function setOrganizationHoursOpen(string $newOrganizationHoursOpen): void {
		//validate hours of operation string security
		$newOrganizationHoursOpen = trim($newOrganizationHoursOpen);
		$newOrganizationHoursOpen = filter_var($newOrganizationHoursOpen, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationHoursOpen) === true) {
			throw(new \InvalidArgumentException("sorry, but the hours of operation you gave is not safe or empty"));
		}
		//verify the string is within 64 characters
		if(strlen($newOrganizationHoursOpen) > 64) {
			throw(new \RangeException("sorry, but the hours of operation must not be greater that 64 characters"));
		}
		//save the string
		$this->organizationHoursOpen = $newOrganizationHoursOpen;
	}

	/**
	 * accessor method for organization name
	 *
	 * @return string organization name
	 **/
	public function getOrganizationName(): string {
		return ($this->organizationName);
	}

	/**
	 * mutator method for organization name
	 *
	 * @param string $newOrganizationName name of the organization
	 * @throws \InvalidArgumentException if $newOrganizationName is not safe or not a string
	 * @throws \RangeException if name string is not less than or equal to 255 characters
	 * @throws \TypeError if there is a typo or response is not a string
	 **/
	public function setOrganizationName(string $newOrganizationName): void {
		//validate name security
		$newOrganizationName = trim($newOrganizationName);
		$newOrganizationName = filter_var($newOrganizationName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationName) === true) {
			throw(new \InvalidArgumentException("sorry, but the organization name you gave is not safe or empty"));
		}
		//verify the string is within 255 characters
		if(strlen($newOrganizationName) > 255) {
			throw(new \RangeException("sorry, but the name of the organization must not be greater that 255 characters"));
		}
		//save the string
		$this->organizationName = $newOrganizationName;
	}

	/**
	 * accessor method for organization phone number
	 *
	 * @return string organization phone number
	 **/
	public function getOrganizationPhone(): string {
		return ($this->organizationPhone);
	}

	/**
	 * mutator method for organization phone number
	 *
	 * @param string $newOrganizationPhone phone number of the organization
	 * @throws \InvalidArgumentException if $newOrganizationPhone is not safe or not a string
	 * @throws \RangeException if phone string is not less than or equal to 32 characters
	 * @throws \TypeError if there is a typo or response is not a string
	 **/
	public function setOrganizationPhone(string $newOrganizationPhone): void {
		//validate phone security
		$newOrganizationPhone = trim($newOrganizationPhone);
		$newOrganizationPhone = filter_var($newOrganizationPhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newOrganizationPhone) === true) {
			throw(new \InvalidArgumentException("sorry, but the organization phone number you gave is not safe or empty"));
		}
		//verify the string is within 32 characters
		if(strlen($newOrganizationPhone) > 32) {
			throw(new \RangeException("sorry, but the phone number must not be greater that 32 characters"));
		}
		//save the string
		$this->organizationPhone = $newOrganizationPhone;
	}

	/**
	 * accessor method for password salt
	 *
	 * @return string representation of the salt hexadecimal
	 **/
	public function getOrganizationSalt(): string {
		return $this->organizationSalt;
	}

	/**
	 * mutator method for organization password salt
	 *
	 * @param string $newOrganizationSalt
	 * @throws \InvalidArgumentException if $newOrganizationSalt is not safe or not a string
	 * @throws \RangeException if password salt is not exactly 64 characters
	 * @throws \Exception if there is another exception, or if salt is not returned as a string
	 **/
	public function setOrganizationSalt(string $newOrganizationSalt): void {
		//validate that salt is correctly formatted
		$newOrganizationSalt = trim($newOrganizationSalt);
		$newOrganizationSalt = strtolower($newOrganizationSalt);
		//validate salt as a string representation of a hexadecimal
		if(!ctype_xdigit($newOrganizationSalt)) {
			throw(new \InvalidArgumentException("sorry, but password salt is not secure or it is empty"));
		}
		//validate salt is exactly 64 characters
		if(strlen($newOrganizationSalt) !== 64) {
			throw (new \RangeException("sorry, but password salt has to be 64 characters"));
		}
		//save the password salt
		$this->organizationSalt = $newOrganizationSalt;
	}

	/**
	 *accessor method for organization Url
	 *
	 *@return string of organization Url
	 **/
	public function getOrganizationUrl(): string {
		return ($this->organizationUrl);
	}

	/**
	 * mutator method for organization Url
	 *
	 * @param string $newOrganizationUrl returned value of organizations Url
	 * @throws \InvalidArgumentException if $newOrganizationUrl is not safe or not a string
	 * @throws \RangeException if Url is not equal to or less than 255 characters
	 * @throws \Exception if there is another exception, or if Url is not returned as a string
	 */
	public function setOrganizationUrl(string $newOrganizationUrl): void {
		//validate Url is safe
		$newOrganizationUrl = trim($newOrganizationUrl);
		$newOrganizationUrl = filter_var($newOrganizationUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		//validate that url is less than or equal to 255 characters
		if(strlen($newOrganizationUrl) > 255) {
			throw(new \RangeException("sorry, but the url you have given must be less than 255 characters"));
		}
		//save organization url
		$this->organizationUrl = $newOrganizationUrl;
	}

	/**
	 * inserts the organization profile into the MySQL database
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when any MySQL related error occurs
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		//this will create a query template
		$query = "INSERT INTO organization(organizationId, organization )"
	}
}
