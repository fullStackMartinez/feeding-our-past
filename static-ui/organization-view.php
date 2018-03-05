<!DOCTYPE html>
<html lang="en">

	<?php require_once("head-utils.php"); ?>

	<body>
		<?php require_once("headder.php"); ?>
		<section>
			<div class="container mt-5 pt-5">
				<div class="row justify-content-center">
					<!-- left column for new post form -->
					<div class="col-lg-5">
						<h3>Create a New Event Post</h3>
						<form id="new-post" action="../public_html/api/post/index.php" method="post">
							<small id="formRequired" class="form-text text-danger">*Required fields</small>
							<div class="form-group">
								<label for="postTitle">Event Title <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="postTitle" placeholder="Title of event">
							</div>
							<div class="form-group">
								<label for="postStartDate">Event Start Date <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="postStartDate" placeholder="Start date">
							</div>
							<div class="form-group">
								<label for="postEndDate">Event End Date <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="postEndDate" placeholder="End date">
							</div>
							<div class="form-group">
								<label for="postContent">Event Description <span class="text-danger">*</span></label>
								<textarea class="form-control" rows="10" id="postContent" placeholder="Description of event"></textarea>
							</div>
							<div class="form-group">
								<label for="postImageUrl">Image?? </label>
								<input type="text" class="form-control" id="postImageUrl" placeholder="URL for image??">
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
					</div>
					<hr>

					<!-- empty middle column for spacing -->
					<div class="col-lg-1"></div>

					<!-- right column for list of posts -->
					<div class="col-lg-6">
						<div class="card mb-4">
							<div class="card-body text-center">
								<h2 class="card-title">Upcoming Events</h2>
								<p class="card-text">This is a wider card with supporting text below as a natural lead-in to
									additional content. This content is a little bit longer.</p>
							</div>
						</div>
						<div class="card mb-4">
							<div class="card-body text-center">
								<h2 class="card-title">Upcoming Events</h2>
								<p class="card-text">This is a wider card with supporting text below as a natural lead-in to
									additional content. This content is a little bit longer.</p>
							</div>
						</div>
						<div class="card mb-4">
							<div class="card-body text-center">
								<h2 class="card-title">Upcoming Events</h2>
								<p class="card-text">This is a wider card with supporting text below as a natural lead-in to
									additional content. This content is a little bit longer.</p>
							</div>
						</div>
						<div class="card mb-4">
							<div class="card-body text-center">
								<h2 class="card-title">Upcoming Events</h2>
								<p class="card-text">This is a wider card with supporting text below as a natural lead-in to
									additional content. This content is a little bit longer.</p>
							</div>
						</div>
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
		</section>
	</body>
</html>