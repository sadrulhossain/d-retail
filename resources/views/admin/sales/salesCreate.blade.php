@extends('admin.index')
@section('title')
Admin Dashboard
@endsection
@section('dashboardTitle')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Sales</h1>
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
            {!! Form::open(['route'=>'salesStore','method'=>'post','enctype'=>'multipart/form-data', 'class'=>'form-horizontal']) !!}
            {{csrf_field()}}
            <div class="row form-group ml-5">
                <div class="col col-md-2">
                    <label for="customer_name" class=" form-control-label">Customer Name</label>
                </div>
                <div class="col-12 col-md-6">
                    {!! Form::text('customer_name',null,['id'=>'customer_name','placeholder'=>'Enter Customer Name','class'=>'form-control'])!!}
                </div>
            </div>
            <table class="table table-bordered" id="products_table">
                <thead>
                    <tr>

                        <th scope="col">Product Name</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $id = 'a' . uniqid();
                    ?>
                    <tr id="product0">
                        <td>
                            {!! Form::select('product['.$id.'][name]',$productList,null,['id'=>'productId_'.$id,'data-key'=>$id,'class'=>'form-control select-product'])!!}
                        </td>
                        <td>
                            {!! Form::text('product['.$id.'][unit_price]',null,['id'=>'unitPrice_'.$id,'data-key'=>$id,'class'=>'form-control'])!!}
                        </td>
                        <td>
                            {!! Form::text('product['.$id.'][quantity]',null,['id'=>'quantity_'.$id,'data-key'=>$id,'class'=>'form-control product-quantity'])!!}
                        </td>
                        <td>
                            {!! Form::text('product['.$id.'][total_price]',null,['id'=>'totalPrice_'.$id,'data-key'=>$id,'class'=>'form-control'])!!}
                        </td>

                        <td>
                            <button  id="addRow" class="btn add-row btn-success"><i class="fa fa-plus-square"></i></button>

                        </td>
                    </tr>
                    <tr id="product1"></tr>

                </tbody>
                <tbody id="newBody"></tbody>
            </table>
            <div class="row form-group ml-5">
                <div class="col col-md-2">

                </div>
                <div class="col col-md-6">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{__('lang.SUBMIT')}}
                    </button>
                    <a href="{{route('sales')}}" class="btn btn-secondary btn-sm">
                        {{__('lang.CANCEL')}}
                    </a>
                </div>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        let row_number = 1;
        $(document).on('click', "#add_r", function (e) {
            e.preventDefault();
            let new_row_number = row_number - 1;
            $('#product' + row_number).html($('#product' + new_row_number).html()).parent("tr").find('td:first');
            $('#products_table').append('<tr id="product' + (row_number + 1) + '"></tr>');
            row_number++;
        });

        $(document).on("click", ".add-row", function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{URL::to('sales/row')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#newBody").prepend(res.html);
                },
            });
        });

        $(document).on('click', '.remove-row', function () {
            $(this).parent().parent().remove();
//            rearrangeSL('contact');
//            return false;
        });

        $(document).on("change", '.select-product', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
             var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var id = $(this).val();
            var key = $(this).attr('data-key');
            if(id>0){
            $.ajax({
                url: "{{URL::to('sales/fetchUnitPrice')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
//                cache: false,
//                contentType: false,
//                processData: false,
                data:{
                    id:id
                },
                success: function (res) {
                    data = JSON.parse(res);
                    $("#unitPrice_"+key).val(data);
                },
            });
        }else{
           $("#unitPrice_"+key).val(' '); 
        }
        });
        
        $(document).on("keyup",'.product-quantity',function(e){
            e.preventDefault();
            var key = $(this).attr('data-key');
            var quantity = $(this).val();
            var unitPrice = $("#unitPrice_"+key).val();
            if(quantity <=0 || isNaN(quantity)){
                $(this).val(' ');
                $(this).focus();
                return false;
            }
            if(unitPrice==' '){
                unitPrice=0;
            }
            var totalPrice = quantity*unitPrice;
            $("#totalPrice_"+key).val(parseFloat(totalPrice).toFixed(2));
            return false;
        });
        
    });
</script>
@endsection




