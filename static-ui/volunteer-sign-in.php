<!DOCTYPE html>
<html lang="en">
	<?php require_once ("head-utils.php");?>
	<body>
		<div class="container">
			<div class="text-center">
				<h2>Sign into your account</h2>
			</div>
<form>
	<div class="form-group">
		<label for="inputEmail">Email address</label>
		<input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" placeholder="Enter volunteer email">
		<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
	</div>
	<div class="form-group">
		<label for="inputPassword">Password</label>
		<input type="password" class="form-control" id="inputPassword" placeholder="Password">
	</div>
	<div class="text-center">
	<button type="submit" class="btn btn-primary">Log in</button>
	</div>
</form>
<h6 class="text-center mt-2"><em>Not yet registered?</em> <a href=""><strong>Sign Up</strong></a> </h6>
		</div>
	</body>
</html>