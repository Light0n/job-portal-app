@extends('layouts.app')

@section('page-style')
<style>
#mainTable tr {
    cursor: pointer;
}
</style>    
@endsection

@section('content')
<div class="container">
    <div class="row mt-5">
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
            <table class="table table-sm table-borderless table-hover" id="mainTable">
            <thead style="display:none">
              <tr>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($jobs as $job)
                <tr job-id="{{$job->id}}">
                <td>
                <div class="card bg-light">
                    <div class="card-header">
                        <div class="row">
                            <h5 class="ml-3"><b>{{$job->title}}</b></h5> 
                            <h5 class="ml-auto mr-3">${{ number_format($job->estimated_budget, 2) }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text mb-2">{{$job->description}}</p>
                        <p class="mb-0">
                        Number of BIDs: <b> {{$job->number_of_application}} </b><br>
                        Skills required: <b>
                        @for ($i = 0; $i < sizeof($job->required_skills); $i++)
                            @if ($i == sizeof($job->required_skills)-1)
                                {{$job->required_skills[$i]->skill_name}}
                            @else
                                {{$job->required_skills[$i]->skill_name . ", "}}
                            @endif
                        @endfor
                        </b>
                        </p>
                    </div>
                </div>
                </td>
                </tr>
            @endforeach
            </tbody></table>

            
        </div>
    </div>
</div>
@endsection

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#mainTable tr').click(function (e) { 
            e.preventDefault();
            //go to a job page
            window.location = '/jobs/' + $(this).attr("job-id");
        });


        $('#mainTable').DataTable({
            "searching": false,
            "lengthChange": false,
            "ordering": false,
            "aaSorting": []//disable initial sort
        });
    });
</script>
@stop
