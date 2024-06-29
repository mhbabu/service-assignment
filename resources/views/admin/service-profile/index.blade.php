@extends('layouts.app')

@section('title', 'Profile List')

@section('header-css')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
@endsection

@section('content')
<div class="container">
    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title fw-bold mb-0">
                <i class="fa fa-list-alt me-2"></i> {{ __('Profile List') }}
            </h4>
            <a class="btn btn-success btn-sm fw-bold" title="Create New" href="{{ route('service-profiles.create') }}">
                <i class="fa fa-plus-circle"></i> Create
            </a>
        </div>
        <div class="card-body border-top p-9">
            <div class="row">
                <div class="col-md-12">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>

    @if(isset($dataTable))
        {!! $dataTable->scripts() !!}
    @endif
@endsection