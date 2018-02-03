<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>entity</title>
	</head>
	<body>
		<h2>Conceptual Model</h2>
		<div>
			<h3>organization</h3>
			<ul>
				<li>organizationId (UUID) [primary key]</li>
				<li>organizationActivationToken (char)</li>
				<li>organizationAddressCity (varchar)</li>
				<li>organizationAddressState (varchar)</li>
				<li>organizationAddressStreet (varchar)</li>
				<li>organizationAddressZip (varchar)</li>
				<li>organizationDonationAccepted (varchar)</li>
				<li>organizationEmail (varchar)</li>
				<li>organizationHash (varchar)</li>
				<li>organizationHoursOpen (varchar)</li>
				<li>organizationName (varchar)</li>
				<li>organizationPhone (varchar)</li>
				<li>organizationSalt (varchar)</li>
				<li>organizationUrl (varchar)</li>
			</ul>
		</div>
		<div>
			<h3>volunteer</h3>
			<ul>
				<li>volunteerId (UUID) [primary key]</li>
				<li>volunteerActivationToken (char) [foreign key] </li>
				<li>volunteerAvailability (varchar)</li>
				<li>volunteerEmail (varchar)</li>
				<li>volunteerHash (varchar)</li>
				<li>volunteerName (varchar)</li>
				<li>volunteerPhone (varchar)</li>
				<li>volunteerSalt (varchar)</li>
			</ul>
		</div>
		<div>
			<h3>post</h3>
			<ul>
				<li>postId (UUID) [primary key]</li>
				<li>postOrganizationId (UUID) [foreign key]</li>
				<li>postContent (varchar)</li>
				<li>postEndDate (date)</li>
				<li>postImageUrL (varchar)</li>
				<li>postStartDate (date)</li>
				<li>postTitle (varchar)</li>
			</ul>
		</div>
		<div>
			<h3>like</h3>
			<ul>
				<li>likeVolunteerID (UUID) [foreign key]</li>
				<li>likePostId (UUID) [foreign key]</li>
			</ul>
		</div>
		<div>
			<h3>Relations</h3>
			<ul>
				<li>One organization can write many posts (1 to n)</li>
				<li>One volunteer can like many posts (1 to n)</li>
				<li>Many volunteers can like many posts (n to m)</li>
				<li>One post can be liked by many volunteers (1 to n)</li>
				<li>Many posts can be liked by many volunteer (n to m)</li>
			</ul>
		</div>
	</body>
</html>