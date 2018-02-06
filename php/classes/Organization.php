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
		} catch(|\InvalidArgumentException | \RangeException | |\TypeError | |\Exception $exception) {
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
		$newOrganizationAddressCity = filter_var($newOrganizationAddressCity, FILTER_SANITZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
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

}
