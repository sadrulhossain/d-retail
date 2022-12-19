@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>User Registration</h1>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="content mt-3">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card ml-5 mr-5">
        <div class="card-body card-block">
            <form action="{{ route('register') }}" method="post" enctype="multipart/form-data" class="form-horizontal" id="form1" runat="server">
                @csrf
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="name" class=" form-control-label">Name</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="email" class=" form-control-label">Email</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="email" name="email" placeholder="Enter Email" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="phone_no" class=" form-control-label">Password</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="password" id="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="password" class=" form-control-label">Confirm Password</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="password" id="password-confirm" name="password_confirmation" placeholder="Password Confirmation" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{__('lang.SUBMIT')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


