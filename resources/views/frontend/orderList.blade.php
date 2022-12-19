@extends('frontend.layouts.default.master')
@section('content')

<div class="container">
    @if(!$orderInfo->isEmpty())
    @foreach($orderInfo as $order)
    <article class="card order-card">
        <header class="card-header"> <strong> Order ID: OD45345345435 </strong> </header>
        <div class="card-body">

            <div class="card">
                <div class="card-body">
                    <div class="col-md-3 order-track-card-item "> <strong>Estimated Delivery time:</strong> <br>29 nov 2019 </div>
                    <div class="col-md-3 order-track-card-item "> <strong>Shipping BY:</strong> <br> BLUEDART, | <i class="fa fa-phone"></i> +1598675986 </div>
                    <div class="col-md-3 order-track-card-item "> <strong>Status:</strong> <br> Picked by the courier </div>
                    <div class="col-md-3 order-track-card-item "> <strong>Tracking #:</strong> <br> BD045903594059 </div>
                </div>
            </div>
            <div class="track">
                <div class="step {{ $order->status >= 0 ? 'active' : ''  }}"> <span class="icon"> <i class="fa fa-spinner fa-spin"></i> </span> <span class="text">Order Pending</span> </div>
                <div class="step {{ $order->status >= 1 ? 'active' : ''  }}"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order confirmed</span> </div>
                <div class="step {{ $order->status >= 2 ? 'active' : ''  }}"> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text"> Picked by courier</span> </div>
                <div class="step {{ $order->status >= 3 ? 'active' : ''  }}"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                <div class="step {{ $order->status >= 4 ? 'active' : ''  }}"> <span class="icon"> <i class="fa fa-handshake-o"></i> </span> <span class="text">Ready for pickup</span> </div>
            </div>
        </div>
    </article>
    @endforeach
    @else
    <h2> Empty Order List </h2>
    @endif

</div>

@stop
