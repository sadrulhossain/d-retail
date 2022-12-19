@extends('admin.index')
@section('title')
Category
@endsection

@section('content')
<div class="content mt-3">
    @if(session('status'))
    <div class="col-sm-12">
        <div class="alert  alert-success alert-dismissible fade show" role="alert">
            <span>{{session('status')}}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong class="card-title">Product List</strong>
            <span class="float-right">
                <a href="{{route('productCreate')}}" class="btn btn-warning">{{__('lang.ADDNEW')}}</a>
            </span>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach($products as $key => $product)
                    <tr>
                        <td>{{ $key }}</td>
                        <!--<td>{{ ++$i }}</td>-->
                        <td>{{$product->name}}</td>
                        <td>{{$product->catName}}</td>
                        <td>
                            @if($product->status==1)
                            <span class="btn btn-success"><small>{{__('lang.ACTIVE')}}</small></span>
                            @else
                            <span class="btn btn-secondary"><small>{{__('lang.INACTIVE')}}</small></span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('productEdit',$product->id)}}" class="btn btn-info">{{__('lang.EDIT')}}</a>
                            <a href="{{route('productDelete',$product->id)}}" onclick="return confirm('Are You Sure!')" class="btn btn-danger">{{__('lang.DELETE')}}</a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

