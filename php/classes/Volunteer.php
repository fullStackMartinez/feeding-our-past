<?php
namespace Edu\Cnm\__\FeedingOurPast;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Volunteer class for capstone project
 *
 * This is a top level entity for Volunteer in the capstone project
 *
 * @author Jolynn Pruitt <jpruitt5@cnm.edu>
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