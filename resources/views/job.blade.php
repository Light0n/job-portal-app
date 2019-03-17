@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Jobs</div>
                    <pre id="content">

                    </pre>
                <div class="card-body">
                    
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
        var job = {!! json_encode($job) !!};
        // console.log(job);
        //print JSON to page
        $("#content").html(JSON.stringify(job, undefined, 4));
    });
</script>
@stop
