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
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.PRODUCT_SKU')</th>
                                        <th class="vcenter">@lang('label.CUSTOMER_DEMAND')</th>
                                        <th class="vcenter">@lang('label.AVAILABLE_QTY')</th>
                                        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                        <th class="vcenter">@lang('label.DELIVERY_QUANTITY')</th>
                                        <th class="vcenter text-center">@lang('label.PRICE')</th>
                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sl = 0; @endphp

                                    @foreach($orderDetailInfo as $target)
                                    <?php
                                    $grandTotal = !empty($target->grand_total) ? $target->grand_total : 0.00;
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{{ $target->product_name }}</td>
                                        <td class="vcenter">{{ $target->sku }}</td>
                                        <td class="vcenter">{{ $target->customer_demand }}</td>
                                        <td class="vcenter">{{ $target->available_quantity }}</td>
                                        <td class="text-center vcenter width-100">
                                            {!! Form::hidden('delivery['.$target->id.'][product_id]', $target->product_id ?? '', ['id' => 'productId']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][product_name]', $target->product_name ?? '', ['id' => 'productName']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][product_sku]', $target->sku ?? '', ['id' => 'productSku']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][customer_demand]', $target->customer_demand ?? '', ['id' => 'customerDemand']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][available_quantity]', $target->available_quantity ?? '', ['id' => 'availableQty']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][sku_id]', $target->sku_id ?? '', ['id' => 'skuId']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][warehouse_id]', $target->warehouse_id ?? '', ['id' => 'warehouseId']) !!}
                                            {!! Form::hidden('delivery['.$target->id.'][quantity]', $target->quantity ?? '') !!}
                                            {!! Form::hidden('delivery['.$target->id.'][unit_price]', $target->unit_price ?? '') !!}
                                            {!! Form::hidden('delivery['.$target->id.'][total_price]', $target->total_price ?? '') !!}
                                            {{ $target->quantity }}
                                        </td>
                                        <td class="text-right vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                {{ Form::text('delivery['.$target->id.'][delivery_quantity]', null, ['id'=>'deliveryQuantity','class'=>'form-control' ,($target->available_quantity < $target->quantity) ? 'disabled' : '' ,'data-original-title'=>'Available quantity is less than customer demand']) }}
                                            </div>
                                        </td>
                                        <td class="text-right vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                {{ $target->unit_price }} &#2547;                                               
                                            </div>
                                        </td>
                                        <td class="text-right vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                 {{ $target->total_price }} &#2547;
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right vcenter width-150" colspan="8">
                                            <strong>@lang('label.GRAND_TOTAL')</strong>
                                        </td>
                                        <td class="text-right vcenter width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                {!! Form::text('grand_total_price', $grandTotal, ['id'=> 'grandTotalPrice', 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per grand-total-price', 'readonly']) !!}
                                                {!! Form::hidden('grand_total_price', $grandTotal ?? '', ['id' => 'grandTotalPrice']) !!}
                                                <span class="input-group-addon bootstrap-touchspin-postfix bold">&#2547;</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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