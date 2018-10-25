@extends('layouts.index')

@section('content')

	<div id="login_page" class="container-fluid d-flex flex-column">

		<div id="page-content" class="row flex-grow-1 d-flex justify-content-center align-items-center">

			<div class="card card-nav-tabs col-11 col-sm-8 col-md-6 col-lg-4 col-xl-3">

				<div class="card-header card-header-primary">

					<h2 class="text-center">
						<strong>Admin Panel</strong>
					</h2>

					<p class="card-category text-center">S3 Web Browser</p>

				</div>

				<div class="card-body">


					<form id="log_in_frm" novalidate>

						<div class="form-group">
							<label for="email" class="bmd-label-floating">Email</label>
							<input type="email" class="form-control" name="email" autocomplete="off"/>
						</div>

						<div class="form-group">
							<label for="password" class="bmd-label-floating">Password</label>
							<input type="password" class="form-control" name="password" autocomplete="off"/>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-primary float-right">Log In</button>
						</div>

					</form>


				</div>

			</div>

		</div>

	</div>

@endsection