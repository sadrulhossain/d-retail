

<div class="col-md-4">
    <strong>@lang('label.SUPPLIER')</strong> : {!! $supplier['name'] ?? '' !!}
    {!! Form::hidden('supplier_id', $supplier['supplier_id'] ?? 0) !!}
</div>
<div class="col-md-12 margin-top-20">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-head-fixer-color">
            <thead>
                <tr>
                    <th class="vcenter text-center">@lang('label.SERIAL')</th>
                    <th class="vcenter text-center width-120">
                        <div class="md-checkbox has-success tooltips" title="@lang('label.SELECT_ALL')">
                            {!! Form::checkbox('check_all',1,false,['id' => 'checkedAll','class'=> 'md-check']) !!} 
                            <label for="checkedAll">
                                <span></span>
                                <span class="check mark-caheck"></span>
                                <span class="box mark-caheck"></span>
                            </label>
                            <span class="bold">@lang('label.CHECK_ALL')</span>
                        </div>
                    </th>
                    <th class="vcenter text-center">@lang('label.PRODUCT_SKU')</th>
                    <th class="vcenter text-center">@lang('label.PURCHASE_QUANTITY')</th>
                    <th class="vcenter text-center">@lang('label.AVAILABLE_STOCK')</th>
                    <th class="vcenter text-center">@lang('label.RETURN_QUANTITY')</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($productArr))
                <?php
                $sl = 0;
                ?>
                @foreach($productArr as $product)
                <?php
                $id = $product['sku_id'];
                $checked = '';
                $disabled = 'disabled';
                $class = 'product-check';

//                             
                ?>
                <tr>
                    <td class="vcenter text-center">{!! ++$sl !!}</td>
                    <td class="vcenter text-center">
                        <div class="md-checkbox has-success tooltips" title="<?php ?>">
                            {!! Form::checkbox('product['.$id.']', $id ,$checked,['id' => $id, 'data-id' => $id, 'class'=> 'md-check '.$class ]) !!}
                            <label for="{{ $id }}">
                                <span class="inc"></span>
                                <span class="check mark-caheck"></span>
                                <span class="box mark-caheck"></span>

                            </label>
                        </div>
                    </td>
                    <td class="vcenter text-left">{!! $product['name'] !!}</td>
                    <td class="vcenter width-150">
                        <div class="input-group bootstrap-touchspin width-140">
                            {!! Form::text('purchase_quantity['.$id.']',$product['purchase_quantity'], ['id'=> 'purchaseQuantity_'.$id, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right product product-'.$id, 'autocomplete' => 'off', 'data-id' => $id, $disabled , 'readonly' ])!!}
                        </div> 

                    </td>
                    <td class="vcenter width-150">
                        <div class="input-group bootstrap-touchspin width-140">
                            {!! Form::text('remaining_quantity['.$id.']',$product['available_quantity'], ['id'=> 'remainingQuantity_'.$id, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right product product-'.$id, 'autocomplete' => 'off', 'data-id' => $id, $disabled , 'readonly' ])!!}
                        </div> 

                    </td>
                    <td class="vcenter width-150">
                        <div class="input-group bootstrap-touchspin width-140">
                            {!! Form::text('return_quantity['.$id.']',null, ['id'=> 'returnQuantity_'.$id, 'class' => 'form-control integer-decimal-only text-input-width-100-per text-right return-quantity product product-'.$id, 'autocomplete' => 'off', 'data-id' => $id, $disabled])!!}
                        </div> 

                    </td>

                    @endforeach

                    @else
                <tr>
                    <td colspan="9" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


<div class="col-md-offset-4 col-md-8">
    <button class="btn btn-circle green btn-submit" id="" type="button">
        <i class="fa fa-check"></i> @lang('label.SUBMIT')
    </button>
    <a href="{{ URL::to('admin/returnProduct') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
</div>

<script type="text/javascript">
    $(function () {
<?php
if (!empty($productArr)) {
    ?>
            $("#checkedAll").on('change', function () {
                if (this.checked) {
                    $('.product-check').prop('checked', true);
                    $('.product').prop('disabled', false);
                } else {
                    $('.product-check').prop('checked', false);
                    $('.product').prop('disabled', true);
                }
            });

            $(document).on('keyup', '.return-quantity', function () {

                var id = $(this).attr("data-id");

                var returnQuantity = parseFloat($(this).val());
                var purchaseQuantity = parseFloat($('#purchaseQuantity_' + id).val());
                var availableQuantity = parseFloat($('#remainingQuantity_' + id).val());

                var x = purchaseQuantity > availableQuantity ? availableQuantity : purchaseQuantity;

                if (x < returnQuantity) {
                    swal({
                        title: "@lang('label.RETURN_QUANTITY_CAN_NO_BE_GRATER_THAN_PURCHASEQUANTITY') ",
                        text: "",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "@lang('label.OK')",
                        closeOnConfirm: true,
                        closeOnCancel: true,
                    }, function (isConfirm) {
                        $('#returnQuantity_' + id).val('');
                        setTimeout(function () {
                            $('#returnQuantity_' + id).focus();
                        }, 250);
                        return false;
                    });
                }


                var y = purchaseQuantity < availableQuantity ? availableQuantity : purchaseQuantity;

                if (y < returnQuantity) {
                    swal({
                        title: "@lang('label.RETURN_QUANTITY_CAN_NO_BE_GRATER_THAN_AVAILABLEQUANTITY') ",
                        text: "",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "@lang('label.OK')",
                        closeOnConfirm: true,
                        closeOnCancel: true,
                    }, function (isConfirm) {
                        $('#returnQuantity_' + id).val('');
                        setTimeout(function () {
                            $('#returnQuantity_' + id).focus();
                        }, 250);
                        return false;
                    });
                }



            });


            $('.product-check').on('change', function () {
                var id = $(this).attr("data-id");
                //console.log(id);
                if (this.checked) {
                    $('.product-' + id).prop('disabled', false);
                } else {
                    $('.product-' + id).prop('disabled', true);
                }

                //check 'check all' if all checkbox items are checked
                allCheck();
            });

        });
    <?php
}
?>

    function allCheck() {
        if ($('.product-check:checked').length == $('.product-check').length) {
            $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
        } else {
            $('#checkedAll')[0].checked = false;
        }
    }
</script>