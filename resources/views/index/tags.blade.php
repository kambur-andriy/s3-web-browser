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
	<script src="/js/tags.js" type="text/javascript"></script>


</head>
<body>

<div class="wrapper ">

	<div class="sidebar" data-color="purple" data-background-color="white" data-image="/images/sidebar.jpg">

		<div class="logo">
			<a href="" class="simple-text logo-normal">Tags Categories</a>
		</div>

		<div class="sidebar-wrapper">

			<ul class="nav">


			</ul>

		</div>

	</div>

	<div class="main-panel">

		<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top">

			<div class="container-fluid">

				<div class="navbar-wrapper">

					<button id="move_back" type="button" class="btn btn-round btn-just-icon">
						<i class="material-icons">arrow_back</i>
					</button>

					<button id="move_forward" type="button" class="btn btn-round btn-just-icon ml-2">
						<i class="material-icons">arrow_forward</i>
					</button>

				</div>

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

					<ul class="navbar-nav"> </ul>
				</div>

			</div>

		</nav>

		<div class="content">

			<div class="container-fluid">

				<div class="row">

					<div class="col-12">

						<div class="card">

							<div class="card-body">

								<div class="table-responsive">

									<table id="content_list" class="table table-hover">

										<thead>

										<tr>

											<th colspan="6">

												<div class="navbar-wrapper">

													<nav id="quick_navigation" aria-label="breadcrumb">
														<ol class="breadcrumb">
														</ol>
													</nav>

												</div>

											</th>

										</tr>

										<tr>

											<th id="current_directory" colspan="6">

												<div class="float-left">
													Current directory: <strong id="directory_name"></strong>
												</div>

												<div class="float-right">
													Directories: <strong id="dir_count"></strong>
													Files: <strong id="files_count"></strong>
												</div>

											</th>

										</tr>

										<tr>

											<th class="td-check">

												<div class="form-check">

													<label class="form-check-label">
														<input id="select_all_files" class="form-check-input" type="checkbox">
														<span class="form-check-sign">
				                                    <span class="check"></span>
												</span>
													</label>

												</div>

											</th>

											<th class="text-warning font-weight-bold">View</th>
											<th class="text-warning font-weight-bold">
												Name
												<i id="sort_content" class="material-icons float-right text-danger asc">sort_by_alpha</i>
											</th>
											<th class="text-warning text-center font-weight-bold">Size</th>
											<th class="text-warning text-center font-weight-bold">Modified</th>
											<th class="text-warning text-center font-weight-bold">Actions</th>


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

</div>

</body>
</html>
