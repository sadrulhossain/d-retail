@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Product Update</h1>
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
            <form action="{{ route('productUpdate',$product->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="product_name" class=" form-control-label">Product Name:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="product_name" name="name" value="{{__($product->name)}}" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="category_name" class=" form-control-label">Select Category:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <select class="form-control" name="category_id">
                            <option value="">Select</option>
                            @foreach(App\Model\Category::where('status',1)->orderBy('name','asc')->get() as $category)
                            <option value="{{__($category->id)}}" <?php
                            if ($category->id == $product->category_id) {
                                echo "selected";
                            }
                            ?>>{{__($category->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="description" class=" form-control-label">Description:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <textarea name="description" class="form-control">{{$product->description}}</textarea>
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="status" class=" form-control-label">Status:</label>
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
                        <label for="unit_price" class=" form-control-label">Unit Price:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="unit_price" name="unit_price" value="{{__($product->unit_price)}}" class="form-control">
                    </div>
                </div>
                <div class="row form-group ml-5">
                    <div class="col col-md-2">
                        <label for="quantity" class=" form-control-label">Quantity:</label>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" id="quantity" name="quantity" value="{{__($product->stock)}}" class="form-control">
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
            </form>
        </div>
    </div>
</div>
@endsection






