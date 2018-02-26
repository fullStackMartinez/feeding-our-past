postId BINARY(16) NOT NULL,
-- this is the foreign key
postOrganizationId BINARY(16) NOT NULL,
postContent VARCHAR(4096) NOT NULL,
postEndDateTime DATETIME(6) NOT NULL,
postImageUrl VARCHAR(255),
postStartDateTime DATETIME(6) NOT NULL,
postTitle VARCHAR(255) NOT NULL,