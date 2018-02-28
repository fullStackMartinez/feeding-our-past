<!DOCTYPE html>
<html lang="en">

	<?php require_once ("head-utils.php");?>

	<body>
		<div class="container p-5">
			<div class="row justify-content-center">
				<h2>Organization Sign-Up</h2>
			</div>
			<form>
				<div class="form-group">
					<label for="organizationName">Organization Name</label>
					<input type="text" class="form-control" id="organizationName" placeholder="Organization name">
				</div>
				<div class="form-group">
					<label for="organizationEmail">Email address</label>
					<input type="email" class="form-control" id="organizationEmail" aria-describedby="emailHelp" placeholder="Enter email">
					<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
				</div>
				<div class="form-group">
					<label for="organizationPassword">Password</label>
					<input type="password" class="form-control" id="organizationPassword" placeholder="Password">
				</div>
				<div class="form-group">
					<label for="organizationPasswordConfirm">Confirm password</label>
					<input type="password" class="form-control" id="organizationPasswordConfirm" placeholder="Confirm password">
				</div>
				<div class="form-group">
					<label for="organizationAddressStreet">Street Address</label>
					<input type="text" class="form-control" id="organizationAddressStreet" placeholder="Street address">
				</div>
				<div class="form-group">
					<label for="organizationAddressCity">City</label>
					<input type="text" class="form-control" id="organizationAddressCity" placeholder="City">
				</div>
				<div class="form-group">
					<label for="organizationAddressState">State</label>
					<input type="text" class="form-control" id="organizationAddressState" placeholder="State">
				</div>
				<div class="form-group">
					<label for="organizationAddressZip">Zip Code</label>
					<input type="text" class="form-control" id="organizationAddressZip" placeholder="Zip code">
				</div>
				<div class="form-group">
					<label for="organizationPhone">Phone number</label>
					<input type="text" class="form-control" id="organizationPhone" placeholder="Phone number (xxx-xxx-xxxx)">
				</div>
				<div class="form-group">
					<label for="organizationHoursOpen">Days/Hours Open</label>
					<input type="text" class="form-control" id="organizationHoursOpen" placeholder="Hours open">
				</div>
				<div class="form-group">
					<label for="organizationDonationsAccepted">Food donations accepted?</label>
					<input type="text" class="form-control" id="organizationDonationsAccepted" placeholder="(yes/no)">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>

		</div>

	</body>

