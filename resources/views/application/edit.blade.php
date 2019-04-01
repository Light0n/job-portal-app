@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-md-center mt-5">
		<div class="col-md-8">
            {{-- return message start --}}
            @if (session("message"))
            {{-- session("message")[0]: message status; 
            session("message")[1]: message content --}}
                <div class="alert alert-{{session("message")[0]}} alert-dismissible fade show" role="alert">
                <strong>{{session("message")[1]}}</strong>
                </div>
            @endif
            {{-- return message end --}}
			<div class="card">
				<div class="card-header">Edit the application for <b>{{$job_title}}</b> </div>
				<div class="card-body">
					<form role="form" method="POST" action="{{ action('JobApplicationController@update', $job_application->job_id) }}">
						{!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PUT">
                        <input name="job_id" type="hidden" value="{{$job_application->job_id}}">
                        <input name="jobseeker_id" type="hidden" value="{{$job_application->jobseeker_id}}">
                        <input name="job_title" type="hidden" value="{{$job_title}}">

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Bid Value</label>

							<div class="col-lg-6">
								<input type="number" min="0" step=".01" class="form-control{{ $errors->has('bid_value') ? ' is-invalid' : '' }}" name="bid_value" value="{{ $job_application->bid_value }}"
								 required>
								@if ($errors->has('bid_value'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('bid_value') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Bid Time (days)</label>

							<div class="col-lg-6">
								<input type="number" min="0" class="form-control{{ $errors->has('bid_completion_day') ? ' is-invalid' : '' }}" name="bid_completion_day" value="{{ $job_application->bid_completion_day }}"
								 required>
								@if ($errors->has('bid_completion_day'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('bid_completion_day') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<div class="col-lg-6 offset-lg-4">
								<button type="submit" class="btn btn-success">
									Update
								</button>
                                <a href="{{action('JobController@show', $job_application->job_id)}}" class="btn btn-primary ">View Job</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('page-js-script')
<script type="text/javascript">
$(document).ready(function () {

});
</script>
@stop