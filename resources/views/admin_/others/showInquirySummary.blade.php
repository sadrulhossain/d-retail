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
                                    <th class="text-center vcenter">@lang('label.BUYER')</th>
                                    <th class="text-center vcenter">@lang('label.BUYER_CONTACT_PERSON')</th>
                                    <th class="text-center vcenter">@lang('label.SALES_PERSON')</th>
                                    <th class="text-center vcenter">@lang('label.PRODUCT')</th>
                                    <th class="text-center vcenter">@lang('label.BRAND')</th>
                                    <th class="text-center vcenter">@lang('label.GRADE')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter">@lang('label.UNIT_PRICE')</th>
                                    <th class="text-center vcenter">@lang('label.TOTAL_PRICE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($inquiryArr))
                                <?php $sl = 0; ?>
                                @foreach($inquiryArr as $inquiryId => $inquiry)
                                <tr>
                                    <td class="text-center vcenter" rowspan="{{$inquiryRowSpanArr[$inquiryId]}}">{!! ++$sl !!}</td>
                                    <td class="vcenter" rowspan="{{$inquiryRowSpanArr[$inquiryId]}}">{!! $inquiry['buyer'] !!}</td>
                                    <td class="vcenter" rowspan="{{$inquiryRowSpanArr[$inquiryId]}}">{!! $inquiry['buyer_contact_person'] !!}</td>
                                    <td class="vcenter" rowspan="{{$inquiryRowSpanArr[$inquiryId]}}">{!! $inquiry['sales_person'] !!}</td>

                                    @if(!empty($inquiry['product']))
                                    <?php $i = 0; ?>
                                    @foreach($inquiry['product'] as $productId => $product)
                                    <?php
                                    if ($i > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter" rowspan="{{$productRowSpanArr[$inquiryId][$productId]}}">{!! $product['product_name'] !!}</td>

                                    @if(!empty($product['brand']))
                                    <?php $j = 0; ?>
                                    @foreach($product['brand'] as $brandId => $brand)
                                    <?php
                                    if ($j > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter" rowspan="{{$brandRowSpanArr[$inquiryId][$productId][$brandId]}}">{!! $brand['brand_name'] !!}</td>
                                    @if(!empty($brand['grade']))
                                    <?php $k = 0; ?>
                                    @foreach($brand['grade'] as $gradeId => $grade)
                                    <?php
                                    if ($k > 0) {
                                        echo '<tr>';
                                    }
                                    ?>
                                    <td class="vcenter">{!! $grade['grade_name'] !!}</td>
                                    <td class="text-right vcenter">{!! Helper::numberFormat2Digit($grade['quantity']) . $grade['unit'] !!}</td>
                                    <td class="text-right vcenter">{!! '$' . Helper::numberFormat2Digit($grade['unit_price']) . $grade['per_unit'] !!}</td>
                                    <td class="text-right vcenter">{!! '$' . Helper::numberFormat2Digit($grade['total_price']) !!}</td>


                                    <?php
                                    if (!empty($brand['grade']) && $k < (count($brand['grade']) - 1)) {
                                        echo '</tr>';
                                    } else if (!empty($brand['grade']) && $k == (count($brand['grade']) - 1)) {
                                        if (!empty($product['brand']) && $j < (count($product['brand']) - 1)) {
                                            echo '</tr>';
                                        } 
                                    }
                                    $k++;
                                    ?>
                                    @endforeach
                                    @endif

                                    <?php $j++; ?>
                                    @endforeach
                                    @endif
                                    <?php $i++; ?>
                                    @endforeach
                                    @endif
                                </tr>
                                @endforeach
                                <tr>
                                    <th class="text-right vcenter" colspan="7">@lang('label.TOTAL_QUANTITY')</th>
                                    <th class="text-right vcenter">{!! Helper::numberFormat2Digit($inquirySummaryArr['total_quantity']). ' ' . __('label.UNIT') !!}</th>
                                    <th class="text-right vcenter">@lang('label.TOTAL_AMOUNT')</th>
                                    <th class="text-right vcenter">{!! '$' . Helper::numberFormat2Digit($inquirySummaryArr['total_amount']) !!}</th>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="10" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
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
