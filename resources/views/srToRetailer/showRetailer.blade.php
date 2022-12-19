<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_RETAILER'): {!! !$retailerArr->isEmpty()?count($retailerArr):0 !!}</span>
        @if(!empty($userAccessArr[42][5]))
        <button class='label label-primary tooltips' type="button" href="#modalRelatedRetailer" id="relatedRetailer"  data-toggle="modal" title="@lang('label.SHOW_RELATED_RETAILER')">
            @lang('label.RETAILER_RELATED_TO_THIS_SR'): {!! !empty($srRelateToRetailer)?count($srRelateToRetailer):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
        </button>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover relation-view">
                <thead>
                    <tr class="active">
                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                        @if(!$retailerArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if(!empty($otherRetailerWhArr)){
                            $allCheckDisabled ='disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-retailer-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif

                        <th class="vcenter">@lang('label.RETAILER_NAME')</th>

                    </tr>
                </thead>
                <tbody>
                    @if(!$retailerArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($retailerArr as $retailer)
                    <?php
                    //check and show previous value
                    $checked = $retailerTitle = '';
                    
                    if (!empty($srRelateToRetailer) && array_key_exists($retailer->id, $srRelateToRetailer)) {
                        $checked = 'checked';
                    }

                    $retailerDisabled = $attributeTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveRetailerArr) && in_array($retailer->id, $inactiveRetailerArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $retailerDisabled = 'disabled';
                        $attributeTooltips = __('label.INACTIVE');
                    }
                    
                    if(!empty($otherRetailerWhArr)){
                        if(array_key_exists($retailer->id,$otherRetailerWhArr)){
                           $retailerDisabled = 'disabled';
                           $retailerTitle = __('label.ALREADY_ASSIGNED_TO_SR',['sr'=> $otherRetailerWhArr[$retailer->id]]);
                        }     
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('retailer['.$retailer->id.']', $retailer->id, $checked, ['id' => $retailer->id, 'data-id'=> $retailer->id,'class'=> 'md-check retailer-check tooltips', 'title'=> $retailerTitle, $retailerDisabled]) !!}
                                <label for="{!! $retailer->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $retailerTitle }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $retailerTitle }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $retailerTitle }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('retailer['.$retailer->id.']', $retailer->id) !!}
                            @endif
                        </td>

                        <td class="vcenter">{!! $retailer->name ?? '' !!}</td>

                        
                        

                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_RETAILER_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            @if(!$retailerArr->isEmpty())
            @if(!empty($userAccessArr[42][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[42][1]))
            <a href="{{ URL::to('/admin/srToRetailer') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function () {
      
<?php if (!$retailerArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".retailer-check").on("click", function () {
            if ($('.retailer-check:checked').length == $('.retailer-check').length) {
                $('.all-retailer-check').prop("checked", true);
            } else {
                $('.all-retailer-check').prop("checked", false);
            }
        });
        $(".all-retailer-check").click(function () {
            if ($(this).prop('checked')) {
                $('.retailer-check').prop("checked", true);
            } else {
                $('.retailer-check').prop("checked", false);
            }

        });
        if ($('.retailer-check:checked').length == $('.retailer-check').length) {
            $('.all-retailer-check').prop("checked", true);
        } else {
            $('.all-retailer-check').prop("checked", false);
        }
    });
</script>