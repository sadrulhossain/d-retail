<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            {{ $retailerName}}@lang('label.CONTACT_PERSON_DETAILS')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="">
                                    <th class="vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter"> {{__('label.NAME')}} </th>
                                    <th class="vcenter"> {{__('label.PHONE')}} </th>
                                    <th class="vcenter"> {{__('label.REMARKS')}} </th>
                                </tr>
                            </thead>
                            @if(!empty($contactPersonArr))
                            <?php $serial = 0; ?> 
                            @foreach($contactPersonArr as $personData)
                            <tr>
                                <td class="text-center vcenter">{!! ++$serial !!}</td>
                                <td class="vcenter">{!! $personData['name'] !!}</td>
                                <td class="vcenter">{!! $personData['phone'] !!}</td>
                                <td class="vcenter">{!! $personData['remarks'] !!}</td>

                            </tr>
                            @endforeach 
                            @else
                            <tr>
                                <td colspan="9">{{trans('label.NO_CONTACT_PERSON_FOUND_FOR_THIS_RETAILER')}}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>
<!-- END:: Contact Person Information-->