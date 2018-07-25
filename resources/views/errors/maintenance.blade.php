@extends('layouts.index')

@section('content')

    <div id="page" class="container-fluid d-flex flex-column">

        <div id="page-content" class="row flex-grow-1 d-flex justify-content-center align-items-center">

            <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">

                <div class="card card-stats">

                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">error_outline</i>
                        </div>

                        <p class="card-category">Site is not available</p>

                        <h3 class="card-title">500 ERROR</h3>
                    </div>

                    <div class="card-footer">

                        <div class="stats">
                            <h4>Sorry for the inconvenience. The site is under maintenance. We will be back, shortly.</h4>

                            <h4 class="text-danger">{{ $message }}</h4>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection