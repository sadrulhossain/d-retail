<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RECEIVE_DETAILS')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '', 'id' =>'setReceiveForm', 'class' => 'form-horizontal')) !!}
    {!! Form::hidden('retailer_id', $request->retailer_id) !!}
    {!! Form::hidden('receive', $receive) !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @lang('label.RETAILER'):&nbsp;<strong> {!! $retailer->name ?? __('label.N_A') !!}</strong>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.INVOICE_NO')</th>
                                <th class="vcenter">@lang('label.ORDER_NO')</th>
                                <th class="vcenter">@lang('label.BL_NO')</th>
                                <th class="vcenter">@lang('label.TRANSACTION_ID')</th>
                                <th class="vcenter text-center">@lang('label.COLLECTION_AMOUNT')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($receiveList))
                            <?php $sl = 0; ?>
                            @foreach($receiveList as $invoiceId => $invoiceDetails)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $invoiceDetails['invoice_no'] !!}</td>
                                <td class="vcenter">{!! $invoiceDetails['order_no'] !!}</td>
                                <td class="vcenter">{!! $invoiceDetails['bl_no'] !!}</td>
                                <td class="vcenter text-right">{!! $invoiceDetails['transaction_id'] !!}</td>
                                <td class="text-center vcenter">
                                    <div class="input-group bootstrap-touchspin">
                                        {!! Form::text('collection_amount['.$invoiceId.']', !empty($invoiceDetails['collection_amount']) ? Helper::numberFormat2Digit($invoiceDetails['collection_amount']) : null, ['id'=> 'collectionAmount_'.$invoiceId, 'style' => ' min-width: 100px', 'class' => 'form-control integer-decimal-only text-input-width text-right collection-amount', 'readonly', 'autocomplete' => 'off']) !!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix bold">@lang('label.TK')</span>
                                    </div>
                                </td>
                                {!! Form::hidden('billed['.$invoiceId.']', $request->billed[$invoiceId]) !!}


                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-success" colspan="20">@lang('label.PAYMENT_OF_ALL_INVOICES_HAS_BEEN_COLLECTED')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="setReceive">@lang('label.CONFIRM_SUBMIT')</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>


