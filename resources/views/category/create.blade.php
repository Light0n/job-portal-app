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
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            @endif
            {{-- return message end --}}
			<div class="card">
				<div class="card-header">Add new Skill Category</div>
				<div class="card-body">
					<form role="form" method="POST" action="{{ url('/category') }}">
						{!! csrf_field() !!}

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Name</label>

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('category_name') ? ' is-invalid' : '' }}" name="category_name" value="{{ old('category_name') }}"
								 required>
								@if ($errors->has('category_name'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('category_name') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Description</label>

							<div class="col-lg-6">
								<textarea type="text" rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}"></textarea>
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
									Add Category
								</button>
                                <a href="{{action('CategoryController@index')}}" class="btn btn-primary ">View all Categories</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection