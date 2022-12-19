{!! Form::select('zone_id', $zoneList, null, ['class' => 'form-control js-source-states', 'id' => 'zoneId']) !!}
<span class="text-danger">{{ $errors->first('zone_id') }}</span>
