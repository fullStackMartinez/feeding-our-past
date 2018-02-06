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
	 */
	private $organizationUrl;

	/**
	 * constructor for this Organization
	 *
	 * @param string|Uuid $newOrganizationId will give id of the organizations profile, or null if a brand new profile
	 * @param string
	 */

}
