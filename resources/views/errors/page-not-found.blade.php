@extends('layouts.index')

@section('content')

    <div id="page" class="container-fluid d-flex flex-column">

        <div id="page-content" class="row flex-grow-1 d-flex justify-content-center align-items-center">

            <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">

                <div class="card card-stats">

                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">error_outline</i>
                        </div>

                        <p class="card-category">Page not found</p>

                        <h3 class="card-title">404 ERROR</h3>
                    </div>

                    <div class="card-footer">

                        <div class="stats">
                            <h4>We are really sorry but the page you requested cannot be found.</h4>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection