<!DOCTYPE html>
<html lang="en">

		<?php require_once("head-utils.php"); ?>

			<nav class="navbar navbar-expand-lg navbar-dark">
				<a class="navbar-brand" routerLink="/"> Feeding Our Past </a><small class="d-none d-md inline-block text-muted mr-auto"><em> A Capstone Project </em></small>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				</button>

				<div *ngIf="isAuthenticated === true" class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ml auto">
						"<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Hello, <span class="text-warning">{{ profileUsername }}</span>
							</a>


							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" routerLink="/posts"><i class="fa fa-pencil"></i>&nbsp;&nbsp;New Post</a>
								<div class="dropdown-divider"></div>
								<button class="btn-link dropdown-item" (click)="signOut();"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;Sign Out</button>
							</div>
						</li>
					</ul>
				</div>
			</nav>



	<body>

	</body>
</html>