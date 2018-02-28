<!DOCTYPE html>
<html lang="en">

	<?php require_once ("head-utils.php");?>

	<body>
		<div class="container p-5">
			<div class="row justify-content-center">
				<h2>Organization Sign-Up</h2>
			</div>
			<form>
				<small id="formRequired" class="form-text text-danger">*Required fields</small>
				<div class="form-group">
					<label for="organizationName">Organization Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationName" placeholder="Organization name">
				</div>
				<div class="form-group">
					<label for="organizationEmail">Email address <span class="text-danger">*</span></label>
					<input type="email" class="form-control" id="organizationEmail" aria-describedby="emailHelp" placeholder="Enter email">
					<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
				</div>
				<div class="form-group">
					<label for="organizationPassword">Password <span class="text-danger">*</span></label>
					<input type="password" class="form-control" id="organizationPassword" placeholder="Password">
				</div>
				<div class="form-group">
					<label for="organizationPasswordConfirm">Confirm password <span class="text-danger">*</span></label>
					<input type="password" class="form-control" id="organizationPasswordConfirm" placeholder="Confirm password">
				</div>
				<div class="form-group">
					<label for="organizationAddressStreet">Street Address <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationAddressStreet" placeholder="Street address">
				</div>
				<div class="form-group">
					<label for="organizationAddressCity">City <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationAddressCity" placeholder="City">
				</div>
				<div class="form-group">
					<label for="organizationAddressState">State <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationAddressState" placeholder="State">
				</div>
				<div class="form-group">
					<label for="organizationAddressZip">Zip Code <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationAddressZip" placeholder="Zip code">
				</div>
				<div class="form-group">
					<label for="organizationPhone">Phone number <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationPhone" placeholder="xxx-xxx-xxxx">
				</div>
				<div class="form-group">
					<label for="organizationHoursOpen">Days/Hours Open <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationHoursOpen" placeholder="Hours open">
				</div>
				<div class="form-group">
					<label for="organizationDonationsAccepted">Food donations accepted? <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="organizationDonationsAccepted" placeholder="Yes or No">
				</div>
				<div class="form-group">
					<label for="organizationUrl">Website</label>
					<input type="text" class="form-control" id="organizationUrl" placeholder="Website address">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</body>

