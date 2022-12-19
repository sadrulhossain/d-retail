<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INQUIRY_SUMMARY_OF_DATE', ['date' => Helper::formatDate($date)])
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr >
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($orderInfo))
                                <?php $sl = 0; ?>
                                @foreach($orderInfo as  $inquiry)
                               
                                <tr>
                                   
                                    <td class="text-center vcenter" >{!! ++$sl !!}</td>
                                    <td class="text-right vcenter">{!! Helper::numberFormat2Digit($inquiry->quantity)  !!}</td>
                                  
                                </tr>
                                @endforeach
                                
                                @else
                                <tr>
                                    <td colspan="11" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
