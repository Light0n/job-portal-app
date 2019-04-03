@extends('layouts.app')

@section('page-style')
<style>
input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.8); /* IE */
  -moz-transform: scale(1.8); /* FF */
  -webkit-transform: scale(1.8); /* Safari and Chrome */
  -o-transform: scale(1.8); /* Opera */
}
</style>
@endsection

@section('content')
<div class="container">
	<div class="row justify-content-md-center mt-5">
		<div class="col-md-12">
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
				<div class="card-header">Edit User Info</div>
				<div class="card-body">
					<form role="form" method="POST" action="{{ action('UserController@update', $user_id) }}">
                    <div class="row"> {{-- start a row--}}
                        <div class="col-md-8">{{-- start 2nd column --}}
                        <h5>All User Skills</h5>
                        <table class="table table-sm table-striped" id="mainTable" style="display:none">
                            <thead>
                            <tr>
                                <th scope="col">Category</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Checked</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($skills as $skill)
                                <td>{{ $skill->category_name }}</td>
                                <td>{{ $skill->skill_name }}</td>
                                <td>{{ $skill->description }}</td>
                                <td align="center">
                                    <input type="checkbox" class="select-skill" 
                                        value="{{$skill->id}}" {{ $skill->selected? "checked" : "" }}>
                                </td>
                                </tr>
                            @endforeach
                        </tbody></table>
                        </div>{{-- end 2nd col 6 --}}
                        <div class="col-md-4"> {{-- start 1st column --}}
						{!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PUT">
						<div style="height:105px"></div>
                        <div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">First Name</label>

							<div class="col-lg-8">
								<input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ $user->first_name }}"
								 required>
								@if ($errors->has('first_name'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('first_name') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Last Name</label>

							<div class="col-lg-8">
								<input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ $user->last_name }}"
								 required>
								@if ($errors->has('last_name'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('last_name') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Phone</label>

							<div class="col-lg-8">
								<input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $user->phone }}"
								 required>

								@if ($errors->has('phone'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('phone') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">City</label>

							<div class="col-lg-8">
								<input type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $user->city }}"
								 required>

								@if ($errors->has('city'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('city') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Province</label>

							<div class="col-lg-8">
								<input type="text" class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" value="{{ $user->province }}"
								 required>

								@if ($errors->has('province'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('province') }}</strong>
								</div>
								@endif
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Country</label>

							<div class="col-lg-8">
								<input type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ $user->country }}"
								 required>

								@if ($errors->has('country'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('country') }}</strong>
								</div>
								@endif
							</div>
						</div>

                        {{-- All Skills selection --}}
                        <div class="form-group row" style="display:none">
							<label class="col-lg-4 col-form-label text-lg-right">Skills</label>

							<div class="col-lg-8">
								<select class="custom-select form-control" name="new_skill_ids[]" multiple>
									@foreach ($skills as $skill)
										<option value="{{$skill->id}}"
                                        {{ $skill->selected? "selected" : "" }}>
                                        {{$skill->skill_name}}</option>
									@endforeach
								</select>

                                {{-- store old skills set --}}
                                <select name="old_skill_ids[]" multiple style="display:none">
									@foreach ($skills as $skill)
										<option value="{{$skill->id}}"
                                        {{ $skill->selected? "selected" : "" }}>
                                        {{$skill->skill_name}}</option>
									@endforeach
								</select>
							</div>
						</div>

                        <div class="form-group row">
							<div class="col-lg-6 offset-lg-4">
                                <button type="submit" class="btn btn-success">
									Update
								</button>
                                <a href="{{route('home')}}" class="btn btn-primary">Done</a>
							</div>
						</div>
                        
                        </div>{{-- end 1st column --}}
                        </div> {{-- end row --}}
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
    //selected skill checkbox change handler
    $('.select-skill').change(function (e) {
        e.preventDefault();

        // console.log($(this).parent('td').next().html());
        // console.log($('.custom-select option[value=' + this.value + ']').html());

        if(this.checked){//set Status table, and select option
            // alert(this.value);
            $('.custom-select option[value=' + this.value + ']').prop('selected', true); 

        }else{//clear Status table, and unselect option
            $('.custom-select option[value=' + this.value + ']').prop('selected', false); 
        }
    });

	/* Create an array with the values of all the checkboxes in a column */
	$.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col )
	{
		return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
			return $('input', td).prop('checked') ? '1' : '0';
		} );
	}

    $('#mainTable').DataTable({
		"columns": [
            null,
            null,
            null,
            { "orderDataType": "dom-checkbox",
			  "orderSequence": [ "desc" ] }],
		"order": [[ 3, "desc" ]]//begin sort column
	});
    $('#mainTable').css('display','table');
});
</script>
@stop