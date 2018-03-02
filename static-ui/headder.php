<!DOCTYPE html>
<html lang="en">
	<?php require_once ("head-utils.php");?>
	<body>
		<nav class="navbar float-top navbar-dark bg-info justify-content-center ">
			<a class="navbar-brand" href="#">Feeding Our Past</a>
		</nav>



		<!--Here I created a div and created a container. my is margins top and bottom mx is margins left to right -->
		<div class="container my-3 mx-5">
			 Here I created a navbar that will expanded when medium. with light text and background and rounded border-->
			<nav class="navbar navbar-expand-md navbar-info justify-content-center bg-light border rounded">
				<!-- the  navbar-brand gets our text in the nav bar -->
				<a class="navbar-brand" href="#">Feeding Our Past</a>
				<!--Here I created buttons that collapse in white. Again sample  -->
				<button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbarToggler">
					<span class="navbar-toggler-icon"></span>
				</button>
				<!-- Here I continue the with the collapsable button "elements" and order them from left to right and justify them on the right or end -->
				<div class="collapse navbar-collapse justify-content-center" id="navbarToggler">
					<!-- Here I create the unordered list which creates the "links"
					The mt settings affect the thickness of the navbar-->
					<ul class="navbar-nav ml-auto mt-0 mt-sm-0">
						<li class="nav-item">
							<a class="nav-link" href="#">We Help</a>
						</li>
						<li class="nav-item align-items-center">
							<a class="nav-link" href="#">I Need Help</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="#">I Want to Help</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Contact Us</a>
						</li>
					</ul>
				</div>
			</nav>
	</body>
</html>