@extends('layouts.app')

@section('content')
<div style="color: #636b6f; font-family: 'Raleway', sans-serif; font-weight: 100; height: 80vh;  
align-items: center; display: flex; justify-content: center;">
    <div style="text-align: center;">
        <div style="font-size: 5rem; margin-bottom: 30px;">
           Welcome to <b>{{ config('app.name', 'Job Portal') }}</b>
        </div>
    
        <div style="color: #636b6f; font-size: 2rem; font-weight: 600; letter-spacing: .1rem;
        text-transform: uppercase;">
            <a href="{{ route('jobs') }}" style="padding: 0 30px; text-decoration: none;">Browse Jobs</a>
            <a href="{{ action('JobController@create') }}" style="padding: 0 30px; text-decoration: none;">Post a Job</a>
        </div>
    </div>
</div>
@endsection