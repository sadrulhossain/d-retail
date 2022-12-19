<div class="row margin-bottom-10">
    <div class="col-md-12">
        <span class="label label-success" >@lang('label.TOTAL_NUM_OF_ATTRIBUTES'): {!! !$attributeArr->isEmpty()?count($attributeArr):0 !!}</span>
        @if(!empty($userAccessArr[92][5]))
        <button class='label label-primary tooltips' href="#modalRelatedAttribute" id="relateAttribute"  data-toggle="modal" title="@lang('label.SHOW_RELATED_ATTRIBUTES')">
            @lang('label.ATTRIBUTES_RELATED_TO_THIS_PRODUCT'): {!! !empty($attributeRelateToProduct)?count($attributeRelateToProduct):0 !!}&nbsp; <i class="fa fa-search-plus"></i>
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
                        @if(!$attributeArr->isEmpty())
                        <?php
                        $allCheckDisabled = '';
                        if (!empty($dependentAttributeArr[$request->product_id])) {
                            $allCheckDisabled = 'disabled';
                        }
                        ?>
                        <th class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-attribute-check', $allCheckDisabled]) !!}
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                &nbsp;&nbsp;<span>@lang('label.CHECK_ALL')</span>
                            </div>
                        </th>
                        @endif

                        <th class="vcenter">@lang('label.ATTRIBUTE_NAME')</th>

                    </tr>
                </thead>
                <tbody>
                    @if(!$attributeArr->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($attributeArr as $attribute)
                    <?php
                    //check and show previous value
                    $checked = '';
                    if (!empty($attributeRelateToProduct) && array_key_exists($attribute->id, $attributeRelateToProduct)) {
                        $checked = 'checked';
                    }

                    $attributeDisabled = $attributeTooltips = '';
                    $checkCondition = 0;
                    if (!empty($inactiveAttributeArr) && in_array($attribute->id, $inactiveAttributeArr)) {
                        if ($checked == 'checked') {
                            $checkCondition = 1;
                        }
                        $attributeDisabled = 'disabled';
                        $attributeTooltips = __('label.INACTIVE');
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('attribute['.$attribute->id.']', $attribute->id, $checked, ['id' => $attribute->id, 'data-id'=> $attribute->id,'class'=> 'md-check attribute-check', $attributeDisabled]) !!}
                                <label for="{!! $attribute->id !!}">
                                    <span class="inc tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                    <span class="check mark-caheck tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                    <span class="box mark-caheck tooltips" data-placement="right" title="{{ $attributeTooltips }}"></span>
                                </label>
                            </div>
                            @if($checkCondition == '1')
                            {!! Form::hidden('attribute['.$attribute->id.']', $attribute->id) !!}
                            @endif
                        </td>

                        <td class="vcenter">{!! $attribute->name ?? '' !!}</td>

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
            @if(!$attributeArr->isEmpty())
            @if(!empty($userAccessArr[92][7]))
            <button class="btn btn-circle green btn-submit" id="" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[92][1]))
            <a href="{{ URL::to('/admin/productToAttribute') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
            @endif
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
//        $('.tooltips').tooltip();
<?php if (!$attributeArr->isEmpty()) { ?>
            $('.relation-view').dataTable({
                "language": {
                    "search": "Search Keywords : ",
                },
                "paging": true,
                "info": true,
                "order": false
            });
<?php } ?>

        $(".attribute-check").on("click", function () {
            if ($('.attribute-check:checked').length == $('.attribute-check').length) {
                $('.all-attribute-check').prop("checked", true);
            } else {
                $('.all-attribute-check').prop("checked", false);
            }
        });
        $(".all-attribute-check").click(function () {
            if ($(this).prop('checked')) {
                $('.attribute-check').prop("checked", true);
            } else {
                $('.attribute-check').prop("checked", false);
            }

        });
        if ($('.attribute-check:checked').length == $('.attribute-check').length) {
            $('.all-attribute-check').prop("checked", true);
        } else {
            $('.all-attribute-check').prop("checked", false);
        }
    });
</script>