@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>User Update</h1>
            </div>
        </div>
    </div>

</div>
@endsection
@section('content')
<div class="content mt-3">


    <div class="card ml-5 mr-5">

        <div class="card-body card-block">
            <form action="{{ route('userUpdate',$user->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row form-group ml-5">
                    <div class="col col-md-1"><label for="name" class=" form-control-label">Name</label></div>
                    <div class="col-12 col-md-6"><input type="text" id="name" name="name" value="{{$user->name}}" class="form-control"></div>
                    
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-1"><label for="email" class=" form-control-label">Email</label></div>
                    <div class="col-12 col-md-6"><input type="text" id="email" name="email" value="{{$user->email}}" class="form-control"></div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-1"><label for="phone_no" class=" form-control-label">Password</label></div>
                    <div class="col-12 col-md-6"><input type="password" id="password" name="password" value="" class="form-control"></div>
                </div>
                
                
                <div class="row form-group ml-5">
                    <div class="col col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{__('lang.UPDATE')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>





</div>
@endsection

