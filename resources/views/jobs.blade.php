@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js-files')
@stop

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function() {
        // alert("Settings page was loaded");
        // alert('jquery ver.' + $.fn.jquery)
        var jobs = {!! json_encode($jobs) !!};
        console.log(jobs);
        $("#content").text(JSON.stringify(jobs));
    });
</script>
@stop
