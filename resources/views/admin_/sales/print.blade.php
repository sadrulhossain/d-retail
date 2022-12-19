<html>
    <head>
        <title>Invoice - {{ $sales->id }}</title>
        
        @if(Request::get('view') == 'print')
        <link rel="stylesheet" href="{{asset('invoice/invoice_style.css')}}">
        @elseif(Request::get('view') == 'pdf')
        <link href="{{ base_path().'/invoice/invoice_style.css'}}" rel="stylesheet" type="text/css"/>
        @endif
<!--        <link rel="stylesheet" href="{{asset('admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}">-->
        <style>
            .content-wrapper{
                background: #FFF;
            }
            .invoice-header {
                background: #f7f7f7;
                padding: 10px 20px 10px 20px;
                border-bottom: 1px solid gray;
            }
            .invoice-header img {

                padding-top: 20px;

            }
            .invoice-right-top h3 {
                padding-right: 20px;
                margin-top: 20px;
                color: #ec5d01;
                font-size: 55px!important;
                font-family: serif;
            }
            .invoice-left-top {
                border-left: 1px solid #ec5d00;
                padding-left: 20px;
                padding-top: 20px;
            }
            thead {
                background: #ec5d01;
                color: #FFF;
            }

            .authority h5 {
                margin-top: -10px;
                color: #ec5d01;
                /*text-align: center;*/
            }
            .thanks h4 {
                color: #ec5d01;
                font-size: 25px;
                font-weight: normal;
                font-family: serif;
                margin-top: 20px;
            }
            .site-address p {
                line-height: 6px;
                font-weight: 300;
            }
            img{
                height: 80px; 
                width: 130px;
            }
            .invoice-description{
                margin-bottom: 50px;
            }

        </style>
    </head>
    <body>

        <div class="content-wrapper">

            <div class="invoice-header">
                <div class="float-left site-logo">
                    <img src="{{ asset('public/invoice/logo/logo.png') }}" alt="">
                </div>
                <div class="float-right site-address">
                    <h4>Swapnoloke</h4>
                    <p>83/2 Borobag, Mirpur-2, Dhaka-1216,Bangladesh</p>
                    <p>Phone: <a href="">01723663924</a></p>
                    <p>Email: <a href="mailto:info@swapnoloke.com">info@swapnoloke.com</a></p>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="invoice-description">
                <div class="invoice-left-top float-left">
                    <h6>Invoice to</h6>
                    <h4>{{ $sales->customer_name }}</h4>


                </div>
                <div class="invoice-right-top float-right">
                    <h4>Invoice #{{ $sales->id }}</h4>
                    <p>
                        Date: {{ $sales->created_at }}
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="">
                <table class="table table-bordered table-striped mb-5">
                    <thead>
                        <tr>
                            <th>test</th>
                            <th>No.</th>
                            <th>Product Title</th>
                            <th>Product Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        @foreach($productData[$sales->id] as $key => $item)
						<tr rowspan="2">
						<td>1</td>
						</tr>
                        <tr>
                            <td>
                                {{$loop->index+1}}
                            </td>
                            <td>
                                {{$productList[$item['product_id']] ?? ''}}
                            </td>
                            <td>
                                {{$item['quantity'] ?? ''}}
                            </td>
                            <td>
                                {{$item['unit_price'] ?? ''}}
                            </td>
                            <td>
                                {{$item['total_price'] ?? ''}}
                                <?php
                                $total += $item['total_price'] ?? '';
                                ?>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"></td>
                            <td>
                                Total:
                            </td>
                            <td>
                                <strong>  {{ $total }}.00 Taka</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="thanks mt-3">
                    <h4>Thank You for Your Shopping..!!</h4>
                </div>

                <div class="authority float-right mt-5">
                    <p>-----------------------------------</p>
                    <h5>Authority Signature:</h5>
                </div>
                <div class="clearfix"></div>
            </div>
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function (event) {
                    window.print();
                });
            </script>
    </body>
</html>