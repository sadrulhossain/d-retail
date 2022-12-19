<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_SR'): {!! !$srArr->isEmpty()?count($srArr):0 !!}</span>
        @if(!empty($userAccessArr[41][5]))
        <button class='label label-primary tooltips' type="button" href="#modalRelatedSR" id="relatedSr"  data-toggle="modal" title="@lang('label.SHOW_RELATED_SR')">
            @lang('label.SR_RELATED_TO_THIS_WAREHOUSE'): {!! !empty($warehouseRelateToSr)?count($warehouseRelateToSr):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                        @if(!$srArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if(!empty($otherSrWhArr)){
                            $allCheckDisabled ='disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-sr-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif

                        <th class="vcenter">@lang('label.SR_NAME')</th>

                    </tr>
                </thead>
                <tbody>
                    @if(!$srArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($srArr as $sr)
                    <?php
                    //check and show previous value
                    $checked = $srTitle = '';
                    
                    if (!empty($warehouseRelateToSr) && array_key_exists($sr->id, $warehouseRelateToSr)) {
                        $checked = 'checked';
                    }

                    $srDisabled = $attributeTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveSrArr) && in_array($sr->id, $inactiveSrArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $srDisabled = 'disabled';
                        $attributeTooltips = __('label.INACTIVE');
                    }
                    
                    if(!empty($otherSrWhArr)){
                        if(array_key_exists($sr->id,$otherSrWhArr)){
                           $srDisabled = 'disabled';
                           $srTitle = __('label.ALREADY_ASSIGNED_TO_WAREHOUSE',['wh'=> $otherSrWhArr[$sr->id]]);
                        }     
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('sr['.$sr->id.']', $sr->id, $checked, ['id' => $sr->id, 'data-id'=> $sr->id,'class'=> 'md-check sr-check tooltips', 'title'=> $srTitle, $srDisabled]) !!}
                                <label for="{!! $sr->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $srTitle }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $srTitle }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $srTitle }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('sr['.$sr->id.']', $sr->id) !!}
                            @endif
                        </td>

                        <td class="vcenter">{!! $sr->full_name ?? '' !!}</td>

                        
                        

                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="vcenter text-danger" colspan="20">@lang('label.NO_BRAND_FOUND')</td>
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
            @if(!$srArr->isEmpty())
            @if(!empty($userAccessArr[41][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[41][1]))
            <a href="{{ URL::to('/admin/warehouseToSr') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function () {
      
<?php if (!$srArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".sr-check").on("click", function () {
            if ($('.sr-check:checked').length == $('.sr-check').length) {
                $('.all-sr-check').prop("checked", true);
            } else {
                $('.all-sr-check').prop("checked", false);
            }
        });
        $(".all-sr-check").click(function () {
            if ($(this).prop('checked')) {
                $('.sr-check').prop("checked", true);
            } else {
                $('.sr-check').prop("checked", false);
            }

        });
        if ($('.sr-check:checked').length == $('.sr-check').length) {
            $('.all-sr-check').prop("checked", true);
        } else {
            $('.all-sr-check').prop("checked", false);
        }
    });
</script>