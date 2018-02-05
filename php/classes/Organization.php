<?php
namespace Edu\Cnm\FeedingOurPast;

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
	 * organization id, this will serve as the primary key for this class
	 * @var Uuid $organizationId
	 **/
	private $organizationId;
	/**
	 *This is the activation token which allows organizations to complete their registration
	 * @var $organizationActivationToken
	 **/
	private $organizationActivationToken;
	/**
	 *organization city, here will be the city of the organization
	 * @var string $organizationAddressCity
	 **/
	private $organizationAddressCity;
	/**
	 * organization state, the state that the city is a part of for the organization
	 * @var string $organizationAddressState
	 **/
	private $organizationAddressState;
	/**
	 * organization street, the street address of the organization
	 * @var string $organizationAddressStreet
	 **/
	private $organizationAddressStreet;

}
