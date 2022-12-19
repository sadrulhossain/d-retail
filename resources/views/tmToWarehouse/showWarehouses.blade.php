<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_WAREHOUSE'): {!! !$warehouseArr->isEmpty()?count($warehouseArr):0 !!}</span>
        @if(!empty($userAccessArr[40][5]))
        <button class='label label-primary tooltips' href="#modalRelatedWarehouse" id="relateWarehouse"  data-toggle="modal" title="@lang('label.SHOW_RELATED_WAREHOUSE')">
            @lang('label.WAREHOUSE_RELATED_TO_THIS_TM'): {!! !empty($warehouseRelateToTm)?count($warehouseRelateToTm):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                        @if(!$warehouseArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-warehouse-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif

                        <th class="vcenter">@lang('label.WAREHOUSE_NAME')</th>

                    </tr>
                </thead>
                <tbody>
                    @if(!$warehouseArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($warehouseArr as $warehouse)
                    <?php
                    //check and show previous value
                    $checked = $tmTitle = '';
                    if (!empty($warehouseRelateToTm) && array_key_exists($warehouse->id, $warehouseRelateToTm)) {
                        $checked = 'checked';
                    }

                    $warehouseDisabled = $attributeTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveWarehouseArr) && in_array($warehouse->id, $inactiveWarehouseArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $warehouseDisabled = 'disabled';
                        $attributeTooltips = __('label.INACTIVE');
                    }
                    if(!empty($otherTmWhArr)){
                        if(array_key_exists($warehouse->id,$otherTmWhArr)){
                           $warehouseDisabled = 'disabled';
                           $tmTitle = __('label.ALREADY_ASSIGNED_TO_TM',['tm'=> $otherTmWhArr[$warehouse->id]]);
                        }     
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('warehouse['.$warehouse->id.']', $warehouse->id, $checked, ['id' => $warehouse->id, 'data-id'=> $warehouse->id,'class'=> 'md-check warehouse-check', $warehouseDisabled]) !!}
                                <label for="{!! $warehouse->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $tmTitle }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $tmTitle }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $tmTitle }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('warehouse['.$warehouse->id.']', $warehouse->id) !!}
                            @endif
                        </td>

                        <td class="vcenter">{!! $warehouse->name ?? '' !!}</td>

                        
                        

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
            @if(!$warehouseArr->isEmpty())
            @if(!empty($userAccessArr[94][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[94][1]))
            <a href="{{ URL::to('/admin/tmToWarehouse') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function () {
//        $('.tooltips').tooltip();
<?php if (!$warehouseArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".warehouse-check").on("click", function () {
            if ($('.warehouse-check:checked').length == $('.warehouse-check').length) {
                $('.all-warehouse-check').prop("checked", true);
            } else {
                $('.all-warehouse-check').prop("checked", false);
            }
        });
        $(".all-warehouse-check").click(function () {
            if ($(this).prop('checked')) {
                $('.warehouse-check').prop("checked", true);
            } else {
                $('.warehouse-check').prop("checked", false);
            }

        });
        if ($('.warehouse-check:checked').length == $('.warehouse-check').length) {
            $('.all-warehouse-check').prop("checked", true);
        } else {
            $('.all-warehouse-check').prop("checked", false);
        }
    });
</script>