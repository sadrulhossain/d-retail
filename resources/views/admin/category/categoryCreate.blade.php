@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Create Category</h1>
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
            <form action="{{ route('categoryStore') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="category_name" class=" form-control-label">Category Name</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="category_name" name="name" placeholder="Enter Category Name" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="status" class=" form-control-label">Select Status</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <select class="form-control" name="status">
                            <option value="1">{{__('lang.ACTIVE')}}</option>
                            <option value="2">{{__('lang.INACTIVE')}}</option>
                            
                        </select>
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        
                    </div>
                    <div class="col col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{__('lang.SUBMIT')}}
                        </button>
                        <a href="{{route('categoryList')}}" class="btn btn-secondary btn-sm">
                            {{__('lang.CANCEL')}}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




