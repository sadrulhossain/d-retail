<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title bold">
            @lang('label.ORDER_INFORMATION')
        </h4>
    </div>
    {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal','files' => true,'id'=>'setDeliveryForm')) !!}
    {{csrf_field()}}
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">

                <div class="form-body">
                    <div class="col-md-12">
                        <div class="row">
                            {!! Form::hidden('order_id', $id) !!}
                            {!! Form::hidden('retailer_id', $orderNo->retailer_id) !!}
                            <div class="form-group">
                                <label class="col-md-4 text-align-end">@lang('label.ORDER_NO') :</label>
                                <div class="col-md-4">
                                    <strong>{{ $orderNo->order_no ?? '' }}</strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="blNo">@lang('label.BL_NO') :<span class="text-danger"> *</span></label>
                                <div class="col-md-4">
                                    {{ Form::text('bl_no', '', ['id'=> 'blNo', 'class' => 'form-control bl-no','size' => '30x2','autocomplete' => 'off']) }}
                                    <span class="text-danger">{{ $errors->first('bl_no') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="blDate">@lang('label.BL_DATE') : <span class="text-danger"> *</span></label>
                                <div class="col-md-4">
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('bl_date', Helper::formatDate($deliveryDate) , ['id'=> 'blDate', 'class' => 'form-control', 'placeholder' =>'DD-MM-YYYY', 'readonly' => '','autocomplete' => 'off']) !!}
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="date">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="expressTrackingNo">@lang('label.EXPRESS_TRACKING_NO') :</label>
                                <div class="col-md-4">
                                    {{ Form::text('express_tracking_no', '', ['id'=> 'expressTrackingNo', 'class' => 'form-control','size' => '30x2','autocomplete' => 'off']) }}
                                    <span class="text-danger">{{ $errors->first('express_tracking_no') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="containerNo">@lang('label.CONTAINER_NO') :</label>
                                <div class="col-md-4">
                                    {{ Form::text('container_no', '', ['id'=> 'containerNo', 'class' => 'form-control','size' => '30x2','autocomplete' => 'off']) }}
                                    <span class="text-danger">{{ $errors->first('container_no') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="highlighted">@lang('label.MARK_AS_LAST_SHIPMENT') :</label>
                                <div class="col-md-8 checkbox-center md-checkbox has-success">
                                    {!! Form::checkbox('mark_as_last_shipment',1,null, ['id' => 'highlighted', 'class'=> 'md-check']) !!}
                                    <label for="highlighted">
                                        <span class="inc"></span>
                                        <span class="check mark-caheck"></span>
                                        <span class="box mark-caheck"></span>
                                    </label>
                                    <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_LAST_SHIPMENT')</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!--<p><b><u>@lang('label.PRODUCT_IMFORMATION'):</u></b></p>-->
                            <table class="table table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                        <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                        <th class="vcenter">@lang('label.CUSTOMER_DEMAND')</th>
                                        <th class="vcenter">@lang('label.STOCK')</th>
                                        <th class="vcenter">@lang('label.AVAILABLE_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.ORDER_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.DELIVERED_QUANTITY')</th>
                                        <th class="vcenter text-center">@lang('label.DUE_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.CHALLAN_QUANTITY')</th>
                                        <th class="vcenter text-center">@lang('label.PRICE')</th>
                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $sl = 0; @endphp

                                    @foreach($orderDetailInfo as $key => $target)
                                    <?php
                                    $freezeStockArr = Common::getFreezeStock($target->warehouse_id, $id);
                                    $grandTotal = !empty($target->grand_total) ? $target->grand_total : 0.00;

                                    $deliveredQty = 0;
                                    $dueQty = $target->quantity;
                                    if (!empty($deliveryDetails[$target->sku_id])) {
                                        $deliveredQty = $deliveryDetails[$target->sku_id];
                                        $dueQty = $target->quantity - $deliveryDetails[$target->sku_id];
                                    }
                                    ?>
                                    @if($dueQty != 0)

                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{{ $target->sku }}</td>
                                        <td class="text-center vcenter">{{ $target->customer_demand }}</td>
                                        <td class="text-center vcenter">{{ $target->available_quantity }}</td>
                                        <td class="text-center vcenter">{{  $availableQuantity = (int)$target->available_quantity-$freezeStockArr[$target->sku_id]  }} </td>
                                        <td class="text-center vcenter width-100">
                                            {!! Form::hidden('delivery['.$target->id.'][product_id]', $target->product_id ?? '', ['id' => 'productId'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][product_name]', $target->product_name ?? '', ['id' => 'productName'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][product_sku]', $target->sku ?? '', ['id' => 'productSku'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][customer_demand]', $target->customer_demand ?? '', ['id' => 'customerDemand'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][available_quantity]', $target->available_quantity ?? '', ['id' => 'availableQty'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][sku_id]', $target->sku_id ?? '', ['id' => 'skuId'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][warehouse_id]', $target->warehouse_id ?? '', ['id' => 'warehouseId'.$target->id]) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][assign_quantity]', $target->quantity ?? null ) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][due]', $dueQty ?? null ) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][delivered_quantity]', $deliveredQty ?? null ) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][unit_price]', $target->unit_price ?? '' ,['id' => 'unitPrice'.$target->id]) !!}
                                            {{ (int)$target->quantity }}
                                        </td>
                                        <!--delivered quantity-->
                                        <td class="text-center vcenter">
                                            {{(int)$deliveredQty}} 
                                        </td>
                                        <!--due-->

                                        <td class="text-center vcenter " id="due{{$target->id}}">
                                            {{(int)$dueQty}}
                                        </td>
                                        <td class="text-right vcenter width-120">
                                            {!! Form::text('delivery['.$target->id.'][quantity]', null,['class' => 'form-control qty text-right integer-decimal-only','data-id'=>$target->id, 'autocomplete' => 'off',($availableQuantity < $dueQty  ? 'disabled' : '')]) !!}

                                        </td>
                                        <td class="text-right vcenter  width-120">
                                            {{ $target->unit_price }} @lang('label.TK')
                                        </td>

                                        <td class="text-right vcenter width-150 width-full">
                                            <div class="input-group bootstrap-touchspin width-inherit" >
                                                {!! Form::text('delivery['.$target->id.'][total_price]', null ,['class' => 'form-control qty text-right integer-decimal-only input-total','id' => 'totalPrice'.$target->id ,'readonly'])  !!}
                                                <span class="input-group-addon bootstrap-touchspin-postfix bold">@lang('label.TK')</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td class="text-right vcenter" colspan="10">
                                            <strong>@lang('label.GRAND_TOTAL')</strong>
                                        </td>
                                        <td class="text-right vcenter width-150" colspan="1">
                                            <div class="input-group bootstrap-touchspin width-full">
                                                {!! Form::text('grand_total_price', '', ['id'=> 'grandTotalPrice', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per grand-total-price','readonly']) !!}
                                                <!--{!! Form::hidden('grand_total_price', $grandTotal ?? '', ['id' => 'grandTotalPriceHidden']) !!}-->
                                                <span class="input-group-addon bootstrap-touchspin-postfix bold">@lang('label.TK')</span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <!--                            <div class="form-group">
                                                            <div class="col-md-10">
                                                                <div class="clearfix margin-top-10">
                                                                    <span class="label label-grey-mint">@lang('label.NOTE')</span>
                                                                    <span class="border-after-grey-mint">{{$orderNo->note ?? ''}} </span>
                                                                </div>
                                                            </div>
                                                        </div>-->
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button class="btn green" id="confirmProceed" data-id="{{$id}}" type="button"><i class="fa fa-check"></i> @lang('label.CONFIRM_PROCEED')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">

    $(document).on("keyup", ".qty", function (e) {
        //grand-total-price availableQty
        //        var spanTotal = $("#spanTotal" + dataId);
        //var grandTotalPriceHidden = $("#grandTotalPriceHidden");
        var dataId = $(this).attr("data-id");
        var thisIn = $(this);
        var thisValue = $(this).val();
        var dueValue = $("#due" + dataId).text();
        var unitPrice = $("#unitPrice" + dataId);
        var totalPrice = $("#totalPrice" + dataId);
        var grandTotalPrice = $("#grandTotalPrice");
        var totalValue = Number($(this).val()) * Number(unitPrice.val());
        totalPrice.val(totalValue.toFixed(2));
        //spanTotal.text(totalValue);
        var sum = 0;
        $(".input-total").each(function () {
            var tot = parseFloat($(this).val());

            if (tot == '' || isNaN(tot)) {
                tot = 0;
            }

            sum = Number(sum) + Number(tot); // Or this.innerHTML, this.innerText
            grandTotalPrice.val(sum.toFixed(2));
            // grandTotalPriceHidden.val(sum);
        });
        if (grandTotalPrice == "0") {
            $("#confirmProceed").prop("disabled", true);
        } else {
            $("#confirmProceed").prop("disabled", false);
        }
        if (parseFloat(thisValue) > parseFloat(dueValue)) {
            swal(
                    {
                        title: "Quantity can not be greater than Due quantity!",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#f27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                        closeOnCancel: true,
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $("#confirmProceed").attr("disabled", true);
                            thisIn.val("");
                            totalPrice.val("");
                            grandTotalPrice.val("");
                            //spanTotal.text("0.00");
                            //grandTotalPriceHidden.val("0.00");
                        }
                    }
            );
        }


    });


</script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
