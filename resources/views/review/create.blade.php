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
				<div class="card-header">Review to 
                @isset($job->reviewed_user_info)
                <b>
                    {{
                    $job->reviewed_user_info->first_name . " " .
                    $job->reviewed_user_info->last_name}}
                </b> {{ " (" . $job->reviewed_user_info->email . ')'}}
                @endisset
                
                </div>
				<div class="card-body">
					<form role="form" method="POST" action="{{ url('/review') }}">
						{!! csrf_field() !!}
                        <input name="review_type" type="hidden" value="{{$review_type}}">
                        <input name="job_id" type="hidden" value="{{$job->id}}">
                        <input name="jobseeker_id" type="hidden" value="{{$job->jobseeker_id}}">
                        <input name="employer_id" type="hidden" value="{{$job->employer_id}}">

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Rate</label>

							<div class="col-lg-6">
								<input type="number" min="0" max="5" step="0.1" class="form-control{{ $errors->has('rate') ? ' is-invalid' : '' }}" name="rate" value="{{ old('rate') }}"
								 required>
								@if ($errors->has('rate'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('rate') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Review</label>

							<div class="col-lg-6">
								<textarea type="text" rows="3" class="form-control{{ $errors->has('review_content') ? ' is-invalid' : '' }}" name="review_content">{{ old('review_content') }}</textarea>
								@if ($errors->has('review_content'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('review_content') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<div class="col-lg-6 offset-lg-4">
								<button type="submit" class="btn btn-success">
									Submit
								</button>
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