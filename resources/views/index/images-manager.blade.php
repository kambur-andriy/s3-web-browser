<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

	<title>S3 Web Browser</title>

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="/css/application.css">
	<link rel="stylesheet" href="/css/theme/material-dashboard.min.css">

	<!--   Core JS Files   -->
	<script src="/js/theme/jquery.min.js" type="text/javascript"></script>
	<script src="/js/theme/popper.min.js" type="text/javascript"></script>
	<script src="/js/theme/bootstrap-material-design.min.js" type="text/javascript"></script>

	<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="/js/theme/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>

	<!--  User scripts    -->
	<script src="/js/images.js" type="text/javascript"></script>


</head>
<body>

<div class="wrapper ">

	<div class="sidebar" data-color="purple" data-background-color="white" data-image="/images/sidebar.jpg">

		<div class="logo">
			<a href="" class="simple-text logo-normal">Images Manager</a>
		</div>

		<div class="sidebar-wrapper">

			<ul class="nav">

				<li class="nav-item">

					<div class="col-10 offset-1 mt-4">

						<form id="image_frm" novalidate>

							<div class="form-group">
								<label for="path" class="bmd-label-floating">File path</label>
								<input type="text" class="form-control-plaintext" name="path" placeholder="Click to open media library ..."
								       autocomplete="off"/>
							</div>

							<div class="form-group">
								<label for="name" class="bmd-label-floating">File name</label>
								<input type="text" class="form-control-plaintext" name="name" autocomplete="off"/>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary w-100">Create Image</button>
							</div>

						</form>

					</div>

				</li>

			</ul>

		</div>

	</div>

	<div class="main-panel">

		<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top">

			<div class="container-fluid">


				<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false"
				        aria-label="Toggle navigation">
					<span class="sr-only">Toggle navigation</span>
					<span class="navbar-toggler-icon icon-bar"></span>
					<span class="navbar-toggler-icon icon-bar"></span>
					<span class="navbar-toggler-icon icon-bar"></span>
				</button>

				<div class="collapse navbar-collapse justify-content-end">

					<form id="search_form" class="navbar-form">

						<div class="input-group no-border">

							<div id="clear_search" class="input-group-prepend d-none">
							      <span class="input-group-text close">
							          <i class="material-icons">close</i>
							      </span>
							</div>

							<input type="text" value="" class="form-control" placeholder="Search...">

							<button type="submit" class="btn btn-white btn-round btn-just-icon">
								<i class="material-icons">search</i>
								<div class="ripple-container"></div>
							</button>

						</div>

					</form>

					<ul class="navbar-nav"></ul>
				</div>

			</div>

		</nav>

		<div class="content">

			<div class="container-fluid">

				<div id="media_library" class="row d-none">

					<div class="col-12">

						<div class="card">


							<div class="card-header card-header-warning">

								<h4 class="card-title">
									Media library

									<button type="button" class="close text-white" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</h4>

							</div>

							<div class="card-body">

								<div class="table-responsive">

									<table id="content_list" class="table table-hover">

										<thead>

										<tr>

											<th colspan="2">

												<div class="navbar-wrapper">

													<nav id="quick_navigation" aria-label="breadcrumb">
														<ol class="breadcrumb">
														</ol>
													</nav>

												</div>

											</th>

										</tr>

										<tr>

											<th id="current_directory" colspan="2">

												<div class="float-left">
													Current directory: <strong id="directory_name"></strong>
												</div>

												<div class="float-right">
													Directories: <strong id="dir_count"></strong>
													Files: <strong id="files_count"></strong>
												</div>

											</th>

										</tr>

										</thead>

										<tbody></tbody>

									</table>

								</div>

							</div>

						</div>

					</div>


				</div>

				<div id="images_library" class="row">

					<div class="col-12">

						<div class="card">

							<div class="card-body">

								<div class="card-header card-header-info">
									<h4 class="card-title">Images List</h4>
								</div>

								<div class="table-responsive">

									<table id="images_list" class="table table-hover">

										<thead>

											<tr id="images_list_headers">
												<th class="text-info">View</th>
												<th class="text-info">Name</th>
												<th class="text-info">Path</th>
												<th class="text-info text-right">Actions</th>
											</tr>

											<tr id="images_list_filters">
												<th colspan="3" class="text-primary">Tags filters</th>
												<th></th>
											</tr>

										</thead>

										<tbody></tbody>

									</table>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>


		</div>

	</div>

</body>
</html>
