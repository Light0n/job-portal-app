@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-md-center mt-5">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Register</div>
				<div class="card-body">
					<form role="form" method="POST" action="{{ url('/register') }}">
						{!! csrf_field() !!}

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">First Name</label>

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}"
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

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}"
								 required>
								@if ($errors->has('last_name'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('last_name') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">E-Mail Address</label>

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"
								 required>

								@if ($errors->has('email'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('email') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Phone</label>

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}"
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

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}"
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

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" value="{{ old('province') }}"
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

							<div class="col-lg-6">
								<input type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}"
								 required>

								@if ($errors->has('country'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('country') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Password</label>

							<div class="col-lg-6">
								<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
								 required>
								@if ($errors->has('password'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('password') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-4 col-form-label text-lg-right">Confirm Password</label>

							<div class="col-lg-6">
								<input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
								 name="password_confirmation" required>
								@if ($errors->has('password_confirmation'))
								<div class="invalid-feedback">
									<strong>{{ $errors->first('password_confirmation') }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row">
							<div class="col-lg-6 offset-lg-4">
								<button type="submit" class="btn btn-primary">
									Register
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