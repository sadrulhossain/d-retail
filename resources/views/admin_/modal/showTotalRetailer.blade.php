
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.RETAILER_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr class="center info">
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter">@lang('label.LOGO')</th>
                                    <th class="vcenter">@lang('label.NAME')</th>
                                    <th class="vcenter">@lang('label.CODE')</th>
                                    <th class="vcenter">@lang('label.ADDRESS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($retailerDataArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($retailerDataArr as $target)
                                <tr>
                                    <td class="text-center vcenter">{{ ++$sl }}</td>
                                    <td class="text-center vcenter" width="30px">
                                        @if(!empty($target['logo']) && file_exists('public/uploads/retailer/'.$target['logo']))
                                        <img class="tooltips" width="50" height="50" src="{{URL::to('/')}}/public/uploads/retailer/{{ $target['logo'] }}" alt="{{ $target['name']}}" title="{{ $target['name'] }}"/>
                                        @else 
                                        <img class="tooltips"  width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png"  alt="{{ $target['name']}}" title="{{ $target['name'] }}"/>
                                        @endif
                                    </td>
                                    <td class="vcenter">{{ $target['name'] }}</td>
                                    <td class="vcenter">{!! $target['code'] !!}</td>
                                    <td class="vcenter">{!! $target['address'] !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6"> @lang('label.NO_DATA_FOUND')</td>
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


