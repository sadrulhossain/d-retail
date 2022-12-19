@if(sizeof($branchList) > '0')
<div class="form-group">
    <label class="control-label col-md-4" for="branchId">@lang('label.BRANCH') :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        {!! Form::select('branch_id', array('0' => __('label.SELECT_BRANCH_OPT')) + $branchList, null, ['class' => 'form-control js-source-states', 'id' => 'branchId']) !!}
        <span class="text-danger">{{ $errors->first('branch_id') }}</span>
    </div>
</div>

<div id="branchDetails">
    
</div>
@else

<div class="alert alert-danger alert-dismissable">
    <p><i class="fa fa-bell-o fa-fw"></i>@lang('label.NO_BRANCH_FOUND')</p>
</div>
@endif