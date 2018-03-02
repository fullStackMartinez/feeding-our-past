<!DOCTYPE html>
<html lang="en">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php require_once ("head-utils.php");?>



	<body>
		<?php require_once ("headder.php");?>
		<div class="container bg-info">
			<div class="row" style="padding-top:  150px">
				<div class="col-md-4">
					<div class="card mb-4">
						<div class="card-body bg-white">
							<h1 class="card-title">Organization</h1>
							<p class="card-text text-muted">this is aligned left text.  the text will hopefully enlarge the size of the card.</p>
							<input type="button" onclick="location.href='organization-sign-up.php';" value="Sign up" />
							<input type="button" onclick="location.href='organization-sign-in.php';" value="Sign in" />
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card mb-4">
						<div class="card-body bg-white text-center">
							<h1 class="card-title">Seniors</h1>
							<p class="card-text">If you are in need of food assistance please click here.</p>
							<input type="button" onclick="location.href='organization-sign-up.php';" value="Sign up" />
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card mb-4">
						<div class="card-body bg-white text-right">
							<h1 class="card-title">Volunteers</h1>
							<p class="card-text text-muted">this is aligned left text.  the text will hopefully enlarge the size of the card.</p>
							<input type="button" onclick="location.href='volunteer-sign-up.php';" value="sign up" />
							<input type="button" onclick="location.href='volunteer-sign-in.php';" value="Sign in" />
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container mt-5">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card mb-4">
						<div class="card-body text-center">
							<h2 class="card-title">Upcoming Events</h2>
							<p class="card-text">This is a wider card with supporting text below as a natural lead-in to
								additional content. This content is a little bit longer.</p>
						</div>
					</div>
				</div>
			</div>
		</div>


	</body>
</html>
