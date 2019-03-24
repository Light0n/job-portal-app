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
				<div class="card-header">Edit a Skill</div>
				<div class="card-body">
					<form role="form" method="POST" action="{{ action('SkillController@update', $skill_id) }}">
						{!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PUT">
						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Category</label>

							<div class="col-lg-6">
								<select class="custom-select form-control" name="category_id">
									@foreach ($categories as $category)
										<option value="{{$category->id}}"
                                        {{ $category->id == $skill->category_id? "selected" : "" }}>
                                        {{$category->category_name}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Name</label>

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('skill_name') ? ' is-invalid' : '' }}" name="skill_name" value="{{ $skill->skill_name }}"
								 required>
								@if ($errors->has('skill_name'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('skill_name') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Description</label>

							<div class="col-lg-6">
								<textarea type="text" rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description">{{ $skill->description }}</textarea>
								@if ($errors->has('description'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('description') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<div class="col-lg-6 offset-lg-4">
								<button type="submit" class="btn btn-success">
									Update Skill
								</button>
                                <a href="{{action('SkillController@index')}}" class="btn btn-primary ">View all Skills</a>
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
    $('.alert').delay(2000).slideUp("slow");
});
</script>
@stop