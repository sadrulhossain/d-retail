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
            <strong class="card-title">Sales List</strong>
            <span class="float-right">
                <a href="{{route('salesCreate')}}" class="btn btn-warning">{{__('lang.ADDNEW')}}</a>
            </span>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Serial</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Product</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$sales->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($sales as $sale)
                    <tr>
                        <td rowspan="{{ $productRowSpan[$sale->id] }}" class="text-center align-middle">{{ ++ $sl }}</td>
                        <td rowspan="{{ $productRowSpan[$sale->id] }}" class="text-center align-middle">{{ $sale->customer_name ?? ''}}</td>
                        @if(!empty($productData[$sale->id]))
                        <?php $i = 0; ?>
                        @foreach($productData[$sale->id] as $key => $item)
                        <?php
                        if ($i > 0) {
                            echo '<tr>';
                        }
                        ?>
                        <td>{{ $productList[$item['product_id']] ?? ''}}</td>
                        <td>{{ $item['unit_price'] ?? ''}}</td>
                        <td>{{ $item['quantity'] ?? ''}}</td>
                        <td>{{ $item['total_price'] ?? ''}}</td>
                        @if($i == 0)
                        <td rowspan="{{ $productRowSpan[$sale->id] }}" class="text-center align-middle">
                            <a href="{{URL::to('/sales/invoiceGenerate/' . $sale->id . '?view=pdf')}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>&nbsp;&nbsp;   
                            <a href="{{URL::to('/sales/invoiceGenerate/' . $sale->id . '?view=print')}}" target="_blank"><i class="fa fa-print"></i></a>&nbsp;&nbsp;
                            <a href="{{URL::to('/sales/invoiceGenerate/' . $sale->id . '?view=xlsx')}}" target="_blank"><i class="fa fa-file-excel-o"></i></a>
                        </td>
                        @endif

                        <?php
                        if ($i < ($productRowSpan[$sale->id] - 1)) {
                            echo '</tr>';
                        }
                        $i++;
                        ?>
                        @endforeach

                        @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif



                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            <div>
            {{ $sales->links() }}
        </div>
        </div>
        
    </div>
</div>
@endsection

