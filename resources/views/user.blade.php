@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">User</div>
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
        var user = {!! json_encode($user) !!};
        console.log(user);
        //print JSON to page
        $("#content").html(JSON.stringify(user, undefined, 4));
    });
</script>
@stop
