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