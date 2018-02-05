ALTER DATABASE feeding_our_past CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `organization`;
DROP TABLE IF EXISTS `volunteer`;
DROP TABLE IF EXISTS `post`;
DROP TABLE IF EXISTS `favorite`;

CREATE TABLE organization (
	-- this is the primary key
	organizationId BINARY(16) NOT NULL,
	organizationActivationToken CHAR(32),
	organizationAddressCity VARCHAR(32) NOT NULL,
	organizationAddressState VARCHAR(32) NOT NULL,
	organizationAddressStreet VARCHAR(32) NOT NULL,
	organizationAddressZip VARCHAR(32) NOT NULL,
	organizationDonationAccepted VARCHAR(32) NOT NULL,
	organizationEmail VARCHAR(128) NOT NULL,
	organizationHash CHAR (128) NOT NULL,
	organizationHoursOpen VARCHAR(32) NOT NULL,
	organizationName VARCHAR(255) NOT NULL,
	organizationPhone VARCHAR(32) NOT NULL,
	organizationSalt CHAR(64) NOT NULL,
	organizationUrl VARCHAR(255),
	-- to make sure duplicate data cannot exist, create a unique index
	UNIQUE(organizationId),
	UNIQUE(organizationEmail),
	-- primary key is organizationId
	PRIMARY KEY(organizationId)
);

CREATE TABLE volunteer (
	-- this is the primary key
	volunteerId BINARY(16) NOT NULL,
	volunteerActivationToken CHAR(32),
	volunteerAvailability VARCHAR(255),
	volunteerEmail VARCHAR(128) NOT NULL,
	volunteerHash CHAR (128) NOT NULL,
	volunteerName VARCHAR(255) NOT NULL,
	volunteerPhone VARCHAR(32) NOT NULL,
	volunteerSalt CHAR(64) NOT NULL,
	-- to make sure duplicate data cannot exist, create a unique index
	UNIQUE(volunteerId),
	UNIQUE(volunteerEmail),
	-- primary key is volunteerId
	PRIMARY KEY(volunteerId)
);