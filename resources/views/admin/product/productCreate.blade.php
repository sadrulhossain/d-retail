@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Create Product</h1>
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
            {!! Form::open(['route'=>'productStore','method'=>'post','enctype'=>'multipart/form-data', 'class'=>'form-horizontal']) !!}
                @csrf
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="product_name" class=" form-control-label">Product Name</label>
                    </div>
                    <div class="col-12 col-md-6">
                        {!! Form::text('name',null,['id'=>'product_name','placeholder'=>'Enter Product Name','class'=>'form-control'])!!}
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="categoryId" class=" form-control-label">Select Category</label>
                    </div>
                    <div class="col-12 col-md-6">
                        {!! Form::select('category_id', $categoryList, null, ['id' => 'categoryId', 'class' => 'form-control']) !!}
                        
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="description" class=" form-control-label">Description</label>
                    </div>
                    <div class="col-12 col-md-6">
                        
                        {!! Form::text('description',null,['id'=>'description','placeholder'=>'Enter Description','class'=>'form-control','rows'=>'9','col'=>'4'])!!}
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="status" class=" form-control-label">Status</label>
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
                        <label for="unit_price" class=" form-control-label">Unit Price</label>
                    </div>
                    <div class="col-12 col-md-6">
                        {!! Form::text('unit_price',null,['id'=>'unit_price','placeholder'=>'Enter Price','class'=>'form-control'])!!}
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="quantity" class=" form-control-label">Quantity</label>
                    </div>
                    <div class="col-12 col-md-6">
                        {!! Form::text('quantity',null,['id'=>'quantity','placeholder'=>'Enter Quantity','class'=>'form-control'])!!}
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">

                    </div>
                    <div class="col col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{__('lang.SUBMIT')}}
                        </button>
                        <a href="{{route('productList')}}" class="btn btn-secondary btn-sm">
                            {{__('lang.CANCEL')}}
                        </a>
                    </div>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection






