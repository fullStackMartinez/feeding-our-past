-- The statement below sets the collation of the database to utf8
ALTER DATABASE feedkitty CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- these statements will drop the tables and re-add them
DROP TABLE IF EXISTS organization;
DROP TABLE IF EXISTS volunteer;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS favorite;

-- create the organization entity
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
	organizationHoursOpen VARCHAR(64) NOT NULL,
	organizationName VARCHAR(255) NOT NULL,
	organizationPhone VARCHAR(32) NOT NULL,
	organizationSalt CHAR(64) NOT NULL,
	organizationUrl VARCHAR(255),
	-- to make sure duplicate data cannot exist, create a unique index
	UNIQUE(organizationEmail),
	-- primary key is organizationId
	PRIMARY KEY(organizationId)
);

-- create the volunteer entity
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
	UNIQUE(volunteerEmail),
	-- primary key is volunteerId
	PRIMARY KEY(volunteerId)
);

-- create the post entity
CREATE TABLE post (
	-- this is the primary key
	postId BINARY(16) NOT NULL,
	-- this is the foreign key
	postOrganizationId BINARY(16) NOT NULL,
	postContent VARCHAR(4096) NOT NULL,
	postEndDateTime DATETIME(6) NOT NULL,
	postImageUrl VARCHAR(255),
	postStartDateTime DATETIME(6) NOT NULL,
	postTitle VARCHAR(255) NOT NULL,
	-- create index before making a foreign key
	INDEX(postOrganizationId),
	-- create the actual foreign key relation
	FOREIGN KEY(postOrganizationId) REFERENCES organization(organizationId),
	-- create the primary key
	PRIMARY KEY(postId)
);

-- create the favorite entity
CREATE TABLE favorite (
	-- these are foreign keys
	favoritePostId BINARY(16) NOT NULL,
	favoriteVolunteerId BINARY(16) NOT NULL,
	-- index the foreign keys
	INDEX(favoritePostId),
	INDEX(favoriteVolunteerId),
	-- create the foreign key relations
	FOREIGN KEY(favoritePostId) REFERENCES post(postId),
	FOREIGN KEY(favoriteVolunteerId) REFERENCES  volunteer(volunteerId),
	-- create a composite foreign key with the two foreign keys
	PRIMARY KEY(favoritePostId, favoriteVolunteerId)
);

