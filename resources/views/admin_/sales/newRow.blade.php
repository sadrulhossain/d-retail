<?php
$id = 'a' . uniqid();
?>

<tr>
    <td>
        {!! Form::select('product['.$id.'][name]',$productList,null,['id'=>'productId_'.$id,'data-key'=>$id,'class'=>'form-control select-product'])!!}
    </td>
    <td>
        {!! Form::text('product['.$id.'][unit_price]',null,['id'=>'unitPrice_'.$id,'data-key'=>$id,'class'=>'form-control'])!!}
    </td>
    <td>
        {!! Form::text('product['.$id.'][quantity]',null,['id'=>'quantity_'.$id,'data-key'=>$id,'class'=>'form-control product-quantity'])!!}
    </td>
    <td>
        {!! Form::text('product['.$id.'][total_price]',null,['id'=>'totalPrice_'.$id,'data-key'=>$id,'class'=>'form-control'])!!}
    </td>
    <td>
        <button  class="btn btn-danger remove-row"><i class="fa fa-minus-square"></i></button>
    </td>
</tr>