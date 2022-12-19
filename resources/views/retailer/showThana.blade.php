{!! Form::select('thana_id', $thanaList, null, ['class' => 'form-control js-source-states', 'id' => 'thanaId']) !!}
<span class="text-danger">{{ $errors->first('thana_id') }}</span>